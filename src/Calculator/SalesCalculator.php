<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;

class SalesCalculator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculate(\DateTime $begin, \DateTime $end)
    {
        /** @var Invoice[] $invoices */
        $invoices = $this->entityManager
            ->createQueryBuilder()
            ->select('i')
            ->from(Invoice::class, 'i')
            ->where('i.invoiceDate BETWEEN :begin and :end')
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();

        $sales = 0;

        foreach ($invoices as $invoice) {
            $sales += $invoice->getTotalGrossPrice();
        }

        return $sales;
    }
}
