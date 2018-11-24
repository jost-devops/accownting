<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Invoice
{
    use CreatedTrait, UpdatedTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Company|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     */
    private $company;

    /**
     * @var Customer|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer")
     */
    private $customer;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $invoiceNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $invoiceDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $timeOfSupply;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $timeOfSupplyEnd;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $creditPeriod;

    /**
     * @var InvoiceLineItem[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\InvoiceLineItem", mappedBy="invoice")
     */
    private $lineItems;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $paid;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     */
    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     */
    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     */
    public function setInvoiceNumber(string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return \DateTime
     */
    public function getInvoiceDate(): \DateTime
    {
        return $this->invoiceDate;
    }

    /**
     * @param \DateTime $invoiceDate
     */
    public function setInvoiceDate(\DateTime $invoiceDate): void
    {
        $this->invoiceDate = $invoiceDate;
    }

    /**
     * @return \DateTime
     */
    public function getTimeOfSupply(): \DateTime
    {
        return $this->timeOfSupply;
    }

    /**
     * @param \DateTime $timeOfSupply
     */
    public function setTimeOfSupply(\DateTime $timeOfSupply): void
    {
        $this->timeOfSupply = $timeOfSupply;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimeOfSupplyEnd(): ?\DateTime
    {
        return $this->timeOfSupplyEnd;
    }

    /**
     * @param \DateTime|null $timeOfSupplyEnd
     */
    public function setTimeOfSupplyEnd(?\DateTime $timeOfSupplyEnd): void
    {
        $this->timeOfSupplyEnd = $timeOfSupplyEnd;
    }

    /**
     * @return int|null
     */
    public function getCreditPeriod(): ?int
    {
        return $this->creditPeriod;
    }

    /**
     * @param int|null $creditPeriod
     */
    public function setCreditPeriod(?int $creditPeriod): void
    {
        $this->creditPeriod = $creditPeriod;
    }

    /**
     * @return InvoiceLineItem[]|Collection
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @return \DateTime|null
     */
    public function getPaid(): ?\DateTime
    {
        return $this->paid;
    }

    /**
     * @param \DateTime|null $paid
     */
    public function setPaid(?\DateTime $paid): void
    {
        $this->paid = $paid;
    }
}
