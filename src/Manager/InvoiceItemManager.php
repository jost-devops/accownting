<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\InvoiceItemDTO;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceItemManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(InvoiceItemDTO $invoiceItemDTO, Invoice $invoice, User $user): InvoiceItem
    {
        $invoiceItem = new InvoiceItem();
        $invoiceItem->setInvoice($invoice);
        $invoiceItem->setTitle($invoiceItemDTO->title);
        $invoiceItem->setDescription($invoiceItemDTO->description);
        $invoiceItem->setAmount($invoiceItemDTO->amount);
        $invoiceItem->setUnit($invoiceItemDTO->unit);
        $invoiceItem->setPriceSingle($invoiceItemDTO->priceSingle);
        $invoiceItem->setVatRate($invoiceItemDTO->vatRate);
        $invoiceItem->setCreated(new \DateTime());
        $invoiceItem->setCreatedBy($user);

        $this->entityManager->persist($invoiceItem);
        $this->entityManager->flush();

        return $invoiceItem;
    }

    public function getEdit(InvoiceItem $invoiceItem): InvoiceItemDTO
    {
        $invoiceItemDTO = new InvoiceItemDTO();
        $invoiceItemDTO->id = $invoiceItem->getId();
        $invoiceItemDTO->title = $invoiceItem->getTitle();
        $invoiceItemDTO->description = $invoiceItem->getDescription();
        $invoiceItemDTO->amount = $invoiceItem->getAmount();
        $invoiceItemDTO->unit = $invoiceItem->getUnit();
        $invoiceItemDTO->priceSingle = $invoiceItem->getPriceSingle();
        $invoiceItemDTO->vatRate = $invoiceItem->getVatRate();

        return $invoiceItemDTO;
    }

    public function edit(
        InvoiceItem $invoiceItem,
        InvoiceItemDTO $invoiceItemDTO,
        User $user
    ): InvoiceItem {
        $invoiceItem->setTitle($invoiceItemDTO->title);
        $invoiceItem->setDescription($invoiceItemDTO->description);
        $invoiceItem->setAmount($invoiceItemDTO->amount);
        $invoiceItem->setUnit($invoiceItemDTO->unit);
        $invoiceItem->setPriceSingle($invoiceItemDTO->priceSingle);
        $invoiceItem->setVatRate($invoiceItemDTO->vatRate);
        $invoiceItem->setUpdated(new \DateTime());
        $invoiceItem->setUpdatedBy($user);

        $this->entityManager->flush();

        return $invoiceItem;
    }

    public function delete(InvoiceItem $invoiceItem): void
    {
        $this->entityManager->remove($invoiceItem);
        $this->entityManager->flush();
    }

    public function duplicate(InvoiceItem $invoiceItem, Invoice $newInvoice, User $user): void
    {
        $newInvoiceItem = clone $invoiceItem;
        $newInvoiceItem->setCreated(new \DateTime());
        $newInvoiceItem->setCreatedBy($user);
        $newInvoiceItem->setInvoice($newInvoice);

        $this->entityManager->persist($newInvoiceItem);
        $this->entityManager->flush();
    }
}
