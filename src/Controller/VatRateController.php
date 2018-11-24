<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\VatRateDTO;
use App\Entity\User;
use App\Entity\VatRate;
use App\Form\VatRateType;
use App\Manager\VatRateManager;
use App\Normalizer\VatRateNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vat-rate")
 */
class VatRateController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('vat-rate/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        VatRateNormalizer $vatRateNormalizer
    ): Response {
        /** @var VatRate[] $vatRates */
        $vatRates = $entityManager->getRepository(VatRate::class)->findAll();

        $response = [
            'data' => [],
        ];

        foreach ($vatRates as $vatRate) {
            $response['data'][] = $vatRateNormalizer->normalize($vatRate);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        VatRateManager $vatRateManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $vatRateDTO = new VatRateDTO();

        $form = $this->createForm(VatRateType::class, $vatRateDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vatRateManager->add($vatRateDTO, $user);

            return $this->redirectToRoute('app_vatrate_index');
        }

        return $this->render('vat-rate/form.html.twig', [
            'title' => 'Add Unit',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        VatRate $vatRate,
        VatRateManager $vatRateManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $vatRateDTO = $vatRateManager->getEdit($vatRate);

        $form = $this->createForm(VatRateType::class, $vatRateDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vatRateManager->edit($vatRate, $vatRateDTO, $user);

            return $this->redirectToRoute('app_vatrate_index');
        }

        return $this->render('vat-rate/form.html.twig', [
            'title' => 'Edit VAT Rate',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        VatRate $vatRate,
        VatRateManager $vatRateManager
    ): Response {
        $vatRateManager->delete($vatRate);

        return $this->redirectToRoute('app_vatrate_index');
    }
}
