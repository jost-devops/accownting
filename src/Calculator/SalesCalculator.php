<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\VatRate;
use Doctrine\ORM\EntityManagerInterface;

class SalesCalculator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculateNet(Company $company, \DateTime $begin, \DateTime $end)
    {
        /** @var Invoice[] $invoices */
        $invoices = $this->entityManager
            ->createQueryBuilder()
            ->select('i')
            ->from(Invoice::class, 'i')
            ->where('i.invoiceDate BETWEEN :begin and :end')
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->andWhere('i.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();

        $sales = 0;

        foreach ($invoices as $invoice) {
            $sales += $invoice->getTotalNetPrice();
        }

        return $sales;
    }

    public function calculateGross(Company $company, \DateTime $begin, \DateTime $end)
    {
        /** @var Invoice[] $invoices */
        $invoices = $this->entityManager
            ->createQueryBuilder()
            ->select('i')
            ->from(Invoice::class, 'i')
            ->where('i.invoiceDate BETWEEN :begin and :end')
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->andWhere('i.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();

        $sales = 0;

        foreach ($invoices as $invoice) {
            $sales += $invoice->getTotalGrossPrice();
        }

        return $sales;
    }

    public function calculateTaxes(Company $company, \DateTime $begin, \DateTime $end, VatRate $vatRate)
    {
        /** @var Invoice[] $invoices */
        $invoices = $this->entityManager
            ->createQueryBuilder()
            ->select('i')
            ->from(Invoice::class, 'i')
            ->join(InvoiceItem::class, 'ii')
            ->where('i.invoiceDate BETWEEN :begin and :end')
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->andWhere('i.company = :company')
            ->setParameter('company', $company)
            ->andWhere('ii.vatRate = :vatRate')
            ->setParameter('vatRate', $vatRate)
            ->getQuery()
            ->getResult();

        $taxes = 0;

        foreach ($invoices as $invoice) {
            $invoiceTaxes = $invoice->getTaxesByRate();
            $taxes += isset($invoiceTaxes[$vatRate->getRate()]) ? $invoiceTaxes[$vatRate->getRate()] : 0;
        }

        return $taxes;
    }
}
