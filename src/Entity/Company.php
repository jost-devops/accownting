<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Company
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $additionalName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $street;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $zip;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $country;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $phone;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $fax;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $website;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $vatNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $taxNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $districtCourt;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $companyRegisterId;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $titleOfManagingDirector;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $managingDirector;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $bankName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $bankIban;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $bankBic;

    /**
     * @var resource|string|null
     *
     * @ORM\Column(type="blob", nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logoMime;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $nextOfferNumber = 1;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $nextInvoiceNumber = 1;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $nextCustomerNumber = 1;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default": "0"})
     */
    private $nextProjectNumber = 1;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getAdditionalName(): ?string
    {
        return $this->additionalName;
    }

    /**
     * @param string|null $additionalName
     */
    public function setAdditionalName(?string $additionalName): void
    {
        $this->additionalName = $additionalName;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getFax(): ?string
    {
        return $this->fax;
    }

    /**
     * @param string|null $fax
     */
    public function setFax(?string $fax): void
    {
        $this->fax = $fax;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     */
    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    /**
     * @param string|null $vatNumber
     */
    public function setVatNumber(?string $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * @return string|null
     */
    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    /**
     * @param string|null $taxNumber
     */
    public function setTaxNumber(?string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    /**
     * @return string|null
     */
    public function getDistrictCourt(): ?string
    {
        return $this->districtCourt;
    }

    /**
     * @param string|null $districtCourt
     */
    public function setDistrictCourt(?string $districtCourt): void
    {
        $this->districtCourt = $districtCourt;
    }

    /**
     * @return string|null
     */
    public function getCompanyRegisterId(): ?string
    {
        return $this->companyRegisterId;
    }

    /**
     * @param string|null $companyRegisterId
     */
    public function setCompanyRegisterId(?string $companyRegisterId): void
    {
        $this->companyRegisterId = $companyRegisterId;
    }

    /**
     * @return string|null
     */
    public function getTitleOfManagingDirector(): ?string
    {
        return $this->titleOfManagingDirector;
    }

    /**
     * @param string|null $titleOfManagingDirector
     */
    public function setTitleOfManagingDirector(?string $titleOfManagingDirector): void
    {
        $this->titleOfManagingDirector = $titleOfManagingDirector;
    }

    /**
     * @return string|null
     */
    public function getManagingDirector(): ?string
    {
        return $this->managingDirector;
    }

    /**
     * @param string|null $managingDirector
     */
    public function setManagingDirector(?string $managingDirector): void
    {
        $this->managingDirector = $managingDirector;
    }

    /**
     * @return string|null
     */
    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    /**
     * @param string|null $bankName
     */
    public function setBankName(?string $bankName): void
    {
        $this->bankName = $bankName;
    }

    /**
     * @return string|null
     */
    public function getBankIban(): ?string
    {
        return $this->bankIban;
    }

    /**
     * @param string|null $bankIban
     */
    public function setBankIban(?string $bankIban): void
    {
        $this->bankIban = $bankIban;
    }

    /**
     * @return string|null
     */
    public function getBankBic(): ?string
    {
        return $this->bankBic;
    }

    /**
     * @param string|null $bankBic
     */
    public function setBankBic(?string $bankBic): void
    {
        $this->bankBic = $bankBic;
    }

    /**
     * @return string|null
     */
    public function getLogo(): ?string
    {
        if (is_resource($this->logo)) {
            $logo = stream_get_contents($this->logo);

            $this->logo = ($logo === false) ? null : $logo;
        }

        return $this->logo;
    }

    /**
     * @param string|null $logo
     */
    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return string|null
     */
    public function getLogoMime(): ?string
    {
        return $this->logoMime;
    }

    /**
     * @param string|null $logoMime
     */
    public function setLogoMime(?string $logoMime): void
    {
        $this->logoMime = $logoMime;
    }

    public function getLogoEncoded(): ?string
    {
        return ($this->getLogo() !== null) ? base64_encode($this->getLogo()) : null;
    }

    /**
     * @return int
     */
    public function getNextOfferNumber(): int
    {
        return $this->nextOfferNumber;
    }

    /**
     * @param int $nextOfferNumber
     */
    public function setNextOfferNumber(int $nextOfferNumber): void
    {
        $this->nextOfferNumber = $nextOfferNumber;
    }

    /**
     * @return int
     */
    public function getNextInvoiceNumber(): int
    {
        return $this->nextInvoiceNumber;
    }

    /**
     * @param int $nextInvoiceNumber
     */
    public function setNextInvoiceNumber(int $nextInvoiceNumber): void
    {
        $this->nextInvoiceNumber = $nextInvoiceNumber;
    }

    /**
     * @return int
     */
    public function getNextCustomerNumber(): int
    {
        return $this->nextCustomerNumber;
    }

    /**
     * @param int $nextCustomerNumber
     */
    public function setNextCustomerNumber(int $nextCustomerNumber): void
    {
        $this->nextCustomerNumber = $nextCustomerNumber;
    }

    /**
     * @return int
     */
    public function getNextProjectNumber(): int
    {
        return $this->nextProjectNumber;
    }

    /**
     * @param int $nextProjectNumber
     */
    public function setNextProjectNumber(int $nextProjectNumber): void
    {
        $this->nextProjectNumber = $nextProjectNumber;
    }
}
