<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Customer
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $customerNumber;

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
    private $salutation;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

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
     * @return int
     */
    public function getCustomerNumber(): int
    {
        return $this->customerNumber;
    }

    /**
     * @param int $customerNumber
     */
    public function setCustomerNumber(int $customerNumber): void
    {
        $this->customerNumber = $customerNumber;
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
    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    /**
     * @param string|null $salutation
     */
    public function setSalutation(?string $salutation): void
    {
        $this->salutation = $salutation;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
