<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\InvoiceDTO;
use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class InvoiceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var InvoiceItemManager
     */
    private $invoiceItemManager;

    public function __construct(EntityManagerInterface $entityManager, InvoiceItemManager $invoiceItemManager)
    {
        $this->entityManager = $entityManager;
        $this->invoiceItemManager = $invoiceItemManager;
    }

    public function add(InvoiceDTO $invoiceDTO, User $user): Invoice
    {
        $invoice = new Invoice();
        $invoice->setCompany($invoiceDTO->company);
        $invoice->setCustomer($invoiceDTO->customer);
        $invoice->setCountry($invoiceDTO->country);
        $invoice->setInvoiceNumber($invoiceDTO->invoiceNumber);
        $invoice->setSubject($invoiceDTO->subject);
        $invoice->setText($invoiceDTO->text);
        $invoice->setInvoiceDate($invoiceDTO->invoiceDate);
        $invoice->setTimeOfSupply($invoiceDTO->timeOfSupply);
        $invoice->setTimeOfSupplyEnd($invoiceDTO->timeOfSupplyEnd);
        $invoice->setCreditPeriod($invoiceDTO->creditPeriod);
        $invoice->setPaid($invoiceDTO->paid);
        $invoice->setCreated(new \DateTime());
        $invoice->setCreatedBy($user);
        $invoice->setPaymentMethod($invoiceDTO->paymentMethod);

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        foreach ($invoiceDTO->items as $itemDTO) {
            $this->invoiceItemManager->add($itemDTO, $invoice, $user);
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
        $invoiceDTO->text = $invoice->getText();
        $invoiceDTO->invoiceDate = $invoice->getInvoiceDate();
        $invoiceDTO->timeOfSupply = $invoice->getTimeOfSupply();
        $invoiceDTO->timeOfSupplyEnd = $invoice->getTimeOfSupplyEnd();
        $invoiceDTO->creditPeriod = $invoice->getCreditPeriod();
        $invoiceDTO->paid = $invoice->getPaid();
        $invoiceDTO->paymentMethod = $invoice->getPaymentMethod();

        foreach ($invoice->getItems() as $item) {
            $invoiceDTO->items[] = $this->invoiceItemManager->getEdit($item);
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
        $invoice->setText($invoiceDTO->text);
        $invoice->setInvoiceDate($invoiceDTO->invoiceDate);
        $invoice->setTimeOfSupply($invoiceDTO->timeOfSupply);
        $invoice->setTimeOfSupplyEnd($invoiceDTO->timeOfSupplyEnd);
        $invoice->setCreditPeriod($invoiceDTO->creditPeriod);
        $invoice->setPaid($invoiceDTO->paid);
        $invoice->setUpdated(new \DateTime());
        $invoice->setUpdatedBy($user);
        $invoice->setPaymentMethod($invoiceDTO->paymentMethod);

        foreach ($invoiceDTO->items as $itemDTO) {
            if ($itemDTO->id !== null) {
                /** @var InvoiceItem|null $item */
                $item = $this->entityManager
                    ->getRepository(InvoiceItem::class)
                    ->findOneBy(['id' => $itemDTO->id]);

                if ($item !== null) {
                    $this->invoiceItemManager->edit($item, $itemDTO, $user);
                }
            } else {
                $this->invoiceItemManager->add($itemDTO, $invoice, $user);
            }
        }

        $itemsToDelete = $invoice->getItems();

        foreach ($itemsToDelete as $key => $item) {
            foreach ($invoiceDTO->items as $itemDTO) {
                if ($itemDTO->id === $item->getId()) {
                    unset($itemsToDelete[$key]);
                }
            }
        }

        foreach ($itemsToDelete as $itemToDelete) {
            $this->entityManager->remove($itemToDelete);
        }

        $this->entityManager->flush();

        return $invoice;
    }

    public function delete(Invoice $invoice): void
    {
        $this->entityManager->remove($invoice);
        $this->entityManager->flush();
    }

    public function duplicate(Invoice $invoice, User $user): void
    {
        $this->entityManager->beginTransaction();

        /** @var Company $company */
        $company = $invoice->getCompany();

        $newInvoice = clone $invoice;
        $newInvoice->setCreated(new \DateTime());
        $newInvoice->setCreatedBy($user);
        $newInvoice->setPaid(null);
        $newInvoice->setInvoiceNumber($company->getNextInvoiceNumber());
        $newInvoice->setInvoiceDate(new \DateTime());

        $this->entityManager->persist($newInvoice);

        $company->setNextInvoiceNumber($company->getNextInvoiceNumber() + 1);

        $this->entityManager->flush();

        foreach ($invoice->getItems() as $item) {
            $this->invoiceItemManager->duplicate($item, $newInvoice, $user);
        }

        $this->entityManager->commit();
    }
}
