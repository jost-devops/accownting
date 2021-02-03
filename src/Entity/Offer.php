<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Offer
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
    private $offerNumber;

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
    private $offerDate;

    /**
     * @var OfferItem[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\OfferItem", mappedBy="offer")
     * @ORM\OrderBy({"position"="ASC", "id"="ASC"})
     */
    private $items;

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default": "de"})
     */
    private $country;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $introText;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalText;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getOfferNumber(): int
    {
        return $this->offerNumber;
    }

    public function setOfferNumber(int $offerNumber): void
    {
        $this->offerNumber = $offerNumber;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getOfferDate(): \DateTime
    {
        return $this->offerDate;
    }

    public function setOfferDate(\DateTime $offerDate): void
    {
        $this->offerDate = $offerDate;
    }

    /**
     * @return OfferItem[]|Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getIntroText(): ?string
    {
        return $this->introText;
    }

    public function setIntroText(?string $introText): void
    {
        $this->introText = $introText;
    }

    public function getAdditionalText(): ?string
    {
        return $this->additionalText;
    }

    public function setAdditionalText(?string $additionalText): void
    {
        $this->additionalText = $additionalText;
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
