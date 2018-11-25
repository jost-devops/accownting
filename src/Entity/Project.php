<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Project
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
    private $name;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $budget;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $pricePerHour;

    /**
     * @var TimeTrackItem[]|Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TimeTrackItem", mappedBy="project")
     */
    private $timeTrackItems;

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
     * @return float|null
     */
    public function getBudget(): ?float
    {
        return $this->budget;
    }

    /**
     * @param float|null $budget
     */
    public function setBudget(?float $budget): void
    {
        $this->budget = $budget;
    }

    /**
     * @return float|null
     */
    public function getPricePerHour(): ?float
    {
        return $this->pricePerHour;
    }

    /**
     * @param float|null $pricePerHour
     */
    public function setPricePerHour(?float $pricePerHour): void
    {
        $this->pricePerHour = $pricePerHour;
    }

    /**
     * @return TimeTrackItem[]|Collection
     */
    public function getTimeTrackItems()
    {
        return $this->timeTrackItems;
    }
}
