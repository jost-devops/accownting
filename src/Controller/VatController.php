<?php declare(strict_types=1);

namespace App\Controller;

use App\Calculator\SalesCalculator;
use App\Entity\VatRate;
use App\Helper\CurrentCompanyHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vat")
 */
class VatController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction(
        CurrentCompanyHelper $currentCompanyHelper,
        SalesCalculator $salesCalculator,
        EntityManagerInterface $entityManager
    ): Response {
        $currentCompany = $currentCompanyHelper->get();
        $vatRates = $entityManager
            ->getRepository(VatRate::class)
            ->findAll();

        $begin = (new \DateTime())
            ->sub(new \DateInterval('P13M'))
            ->modify('first day of this month')
            ->setTime(0, 0, 0);
        $end = (new \DateTime());

        $rows = [];

        do {
            $monthEnd = (clone $begin)
                ->add(new \DateInterval('P1M'))
                ->sub(new \DateInterval('P1D'))
                ->setTime(23, 59, 59);

            $row = [
                'net' => $salesCalculator->calculateNet($currentCompany, $begin, $monthEnd),
                'gross' => $salesCalculator->calculateGross($currentCompany, $begin, $monthEnd),
            ];

            foreach ($vatRates as $vatRate) {
                $row[$vatRate->getId()] = $salesCalculator->calculateTaxes($currentCompany, $begin, $monthEnd, $vatRate);
            }

            $rows[$begin->format('m-Y')] = $row;

            $begin->add(new \DateInterval('P1M'));
        } while ($begin < $end);

        return $this->render('vat/index.html.twig', [
            'rows' => $rows,
            'vatRates' => $vatRates,
        ]);
    }
}