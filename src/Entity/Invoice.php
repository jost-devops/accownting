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
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $invoiceNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $subject;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

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
     * @var InvoiceItem[]|Collection
     *
     * @ORM\OneToMany(targetEntity="InvoiceItem", mappedBy="invoice")
     * @ORM\OrderBy({"position"="ASC", "id"="ASC"})
     */
    private $items;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $paid;

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default": "de"})
     */
    private $country;

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
     * @return int
     */
    public function getInvoiceNumber(): int
    {
        return $this->invoiceNumber;
    }

    /**
     * @param int $invoiceNumber
     */
    public function setInvoiceNumber(int $invoiceNumber): void
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
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
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
     * @return InvoiceItem[]|Collection
     */
    public function getItems()
    {
        return $this->items;
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

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getTotalNetPrice(): float
    {
        $netPrice = 0;

        foreach ($this->getItems() as $item) {
            $netPrice += $item->getAmount() * $item->getPriceSingle();
        }

        return $netPrice;
    }

    public function getTotalTaxes(): float
    {
        $taxes = 0;

        foreach ($this->getItems() as $item) {
            $multiplicator = 0;

            if ($item->getVatRate() !== null) {
                $multiplicator += $item->getVatRate()->getRate() / 100;
            }

            $taxes += $item->getAmount() * ($item->getPriceSingle() * $multiplicator);
        }

        return $taxes;
    }

    public function getTotalGrossPrice(): float
    {
        $grossPrice = 0;

        foreach ($this->getItems() as $item) {
            $multiplicator = 1;

            if ($item->getVatRate() !== null) {
                $multiplicator += $item->getVatRate()->getRate() / 100;
            }

            $grossPrice += $item->getAmount() * ($item->getPriceSingle() * $multiplicator);
        }

        return $grossPrice;
    }

    public function getTaxesByRate(): array
    {
        $taxes = [];

        foreach ($this->getItems() as $item) {
            if ($item->getVatRate() === null) {
                continue;
            }

            if (!isset($taxes[$item->getVatRate()->getRate()])) {
                $taxes[$item->getVatRate()->getRate()] = 0;
            }

            $multiplicator = $item->getVatRate()->getRate() / 100;

            $taxes[$item->getVatRate()->getRate()] +=
                $item->getAmount() * ($item->getPriceSingle() * $multiplicator);
        }

        uksort($taxes, function ($a, $b) {
            if ($a === $b) {
                return 0;
            }

            return ($a < $b) ? -1 : 1;
        });

        return $taxes;
    }
}
