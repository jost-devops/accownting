<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\InvoiceDTO;
use App\Entity\Invoice;
use App\Entity\InvoiceLineItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var InvoiceLineItemManager
     */
    private $invoiceLineItemManager;

    public function __construct(EntityManagerInterface $entityManager, InvoiceLineItemManager $invoiceLineItemManager)
    {
        $this->entityManager = $entityManager;
        $this->invoiceLineItemManager = $invoiceLineItemManager;
    }

    public function add(InvoiceDTO $invoiceDTO, User $user): Invoice
    {
        $invoice = new Invoice();
        $invoice->setCompany($invoiceDTO->company);
        $invoice->setCustomer($invoiceDTO->customer);
        $invoice->setCountry($invoiceDTO->country);
        $invoice->setInvoiceNumber($invoiceDTO->invoiceNumber);
        $invoice->setSubject($invoiceDTO->subject);
        $invoice->setInvoiceDate($invoiceDTO->invoiceDate);
        $invoice->setTimeOfSupply($invoiceDTO->timeOfSupply);
        $invoice->setTimeOfSupplyEnd($invoiceDTO->timeOfSupplyEnd);
        $invoice->setCreditPeriod($invoiceDTO->creditPeriod);
        $invoice->setPaid($invoiceDTO->paid);
        $invoice->setCreated(new \DateTime());
        $invoice->setCreatedBy($user);

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        foreach ($invoiceDTO->lineItems as $lineItemDTO) {
            $this->invoiceLineItemManager->add($lineItemDTO, $invoice, $user);
        }

        return $invoice;
    }

    public function getEdit(Invoice $invoice): InvoiceDTO
    {
        $invoiceDTO = new InvoiceDTO();
        $invoiceDTO->company = $invoice->getCompany();
        $invoiceDTO->customer = $invoice->getCustomer();
        $invoiceDTO->country = $invoice->getCountry();
        $invoiceDTO->invoiceNumber = $invoice->getInvoiceNumber();
        $invoiceDTO->subject = $invoice->getSubject();
        $invoiceDTO->invoiceDate = $invoice->getInvoiceDate();
        $invoiceDTO->timeOfSupply = $invoice->getTimeOfSupply();
        $invoiceDTO->timeOfSupplyEnd = $invoice->getTimeOfSupplyEnd();
        $invoiceDTO->creditPeriod = $invoice->getCreditPeriod();
        $invoiceDTO->paid = $invoice->getPaid();

        foreach ($invoice->getLineItems() as $lineItem) {
            $invoiceDTO->lineItems[] = $this->invoiceLineItemManager->getEdit($lineItem);
        }

        return $invoiceDTO;
    }

    public function edit(
        Invoice $invoice,
        InvoiceDTO $invoiceDTO,
        User $user
    ): Invoice {
        $invoice->setCompany($invoiceDTO->company);
        $invoice->setCustomer($invoiceDTO->customer);
        $invoice->setCountry($invoiceDTO->country);
        $invoice->setInvoiceNumber($invoiceDTO->invoiceNumber);
        $invoice->setSubject($invoiceDTO->subject);
        $invoice->setInvoiceDate($invoiceDTO->invoiceDate);
        $invoice->setTimeOfSupply($invoiceDTO->timeOfSupply);
        $invoice->setTimeOfSupplyEnd($invoiceDTO->timeOfSupplyEnd);
        $invoice->setCreditPeriod($invoiceDTO->creditPeriod);
        $invoice->setPaid($invoiceDTO->paid);
        $invoice->setUpdated(new \DateTime());
        $invoice->setUpdatedBy($user);

        foreach ($invoiceDTO->lineItems as $lineItemDTO) {
            if ($lineItemDTO->id !== null) {
                /** @var InvoiceLineItem|null $lineItem */
                $lineItem = $this->entityManager
                    ->getRepository(InvoiceLineItem::class)
                    ->findOneBy(['id' => $lineItemDTO->id]);

                if ($lineItem !== null) {
                    $this->invoiceLineItemManager->edit($lineItem, $lineItemDTO, $user);
                }
            } else {
                $this->invoiceLineItemManager->add($lineItemDTO, $invoice, $user);
            }
        }

        $lineItemsToDelete = $invoice->getLineItems();

        foreach ($lineItemsToDelete as $key => $item) {
            foreach ($invoiceDTO->lineItems as $lineItemDTO) {
                if ($lineItemDTO->id === $item->getId()) {
                    unset($lineItemsToDelete[$key]);
                }
            }
        }

        foreach ($lineItemsToDelete as $lineItemToDelete) {
            $this->entityManager->remove($lineItemToDelete);
        }

        $this->entityManager->flush();

        return $invoice;
    }

    public function delete(Invoice $invoice): void
    {
        $this->entityManager->remove($invoice);
        $this->entityManager->flush();
    }
}
