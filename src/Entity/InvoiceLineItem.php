<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class InvoiceLineItem
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
     * @var Invoice|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice")
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @var Unit|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Unit")
     */
    private $unit;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $priceSingle;

    /**
     * @var VatRate|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\VatRate")
     */
    private $vatRate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    /**
     * @param Invoice|null $invoice
     */
    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return Unit|null
     */
    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    /**
     * @param Unit|null $unit
     */
    public function setUnit(?Unit $unit): void
    {
        $this->unit = $unit;
    }

    /**
     * @return float
     */
    public function getPriceSingle(): float
    {
        return $this->priceSingle;
    }

    /**
     * @param float $priceSingle
     */
    public function setPriceSingle(float $priceSingle): void
    {
        $this->priceSingle = $priceSingle;
    }

    /**
     * @return VatRate|null
     */
    public function getVatRate(): ?VatRate
    {
        return $this->vatRate;
    }

    /**
     * @param VatRate|null $vatRate
     */
    public function setVatRate(?VatRate $vatRate): void
    {
        $this->vatRate = $vatRate;
    }
}
