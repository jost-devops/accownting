<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\InvoiceLineItemDTO;
use App\Entity\Invoice;
use App\Entity\InvoiceLineItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceLineItemManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(InvoiceLineItemDTO $invoiceLineItemDTO, Invoice $invoice, User $user): InvoiceLineItem
    {
        $invoiceLineItem = new InvoiceLineItem();
        $invoiceLineItem->setInvoice($invoice);
        $invoiceLineItem->setTitle($invoiceLineItemDTO->title);
        $invoiceLineItem->setDescription($invoiceLineItemDTO->description);
        $invoiceLineItem->setAmount($invoiceLineItemDTO->amount);
        $invoiceLineItem->setUnit($invoiceLineItemDTO->unit);
        $invoiceLineItem->setPriceSingle($invoiceLineItemDTO->priceSingle);
        $invoiceLineItem->setVatRate($invoiceLineItemDTO->vatRate);
        $invoiceLineItem->setCreated(new \DateTime());
        $invoiceLineItem->setCreatedBy($user);

        $this->entityManager->persist($invoiceLineItem);
        $this->entityManager->flush();

        return $invoiceLineItem;
    }

    public function getEdit(InvoiceLineItem $invoiceLineItem): InvoiceLineItemDTO
    {
        $invoiceLineItemDTO = new InvoiceLineItemDTO();
        $invoiceLineItemDTO->id = $invoiceLineItem->getId();
        $invoiceLineItemDTO->title = $invoiceLineItem->getTitle();
        $invoiceLineItemDTO->description = $invoiceLineItem->getDescription();
        $invoiceLineItemDTO->amount = $invoiceLineItem->getAmount();
        $invoiceLineItemDTO->unit = $invoiceLineItem->getUnit();
        $invoiceLineItemDTO->priceSingle = $invoiceLineItem->getPriceSingle();
        $invoiceLineItemDTO->vatRate = $invoiceLineItem->getVatRate();

        return $invoiceLineItemDTO;
    }

    public function edit(
        InvoiceLineItem $invoiceLineItem,
        InvoiceLineItemDTO $invoiceLineItemDTO,
        User $user
    ): InvoiceLineItem {
        $invoiceLineItem->setTitle($invoiceLineItemDTO->title);
        $invoiceLineItem->setDescription($invoiceLineItemDTO->description);
        $invoiceLineItem->setAmount($invoiceLineItemDTO->amount);
        $invoiceLineItem->setUnit($invoiceLineItemDTO->unit);
        $invoiceLineItem->setPriceSingle($invoiceLineItemDTO->priceSingle);
        $invoiceLineItem->setVatRate($invoiceLineItemDTO->vatRate);
        $invoiceLineItem->setUpdated(new \DateTime());
        $invoiceLineItem->setUpdatedBy($user);

        $this->entityManager->flush();

        return $invoiceLineItem;
    }

    public function delete(InvoiceLineItem $invoiceLineItem): void
    {
        $this->entityManager->remove($invoiceLineItem);
        $this->entityManager->flush();
    }
}
