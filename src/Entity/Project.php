<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
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
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $projectNumber;

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
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUsed;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": "0"})
     */
    private $archived = false;

    /**
     * @var float|null
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetBilled;

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
     * @return int|null
     */
    public function getProjectNumber(): ?int
    {
        return $this->projectNumber;
    }

    /**
     * @param int|null $projectNumber
     */
    public function setProjectNumber(?int $projectNumber): void
    {
        $this->projectNumber = $projectNumber;
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

    /**
     * @return \DateTime|null
     */
    public function getLastUsed(): ?\DateTime
    {
        return $this->lastUsed;
    }

    /**
     * @param \DateTime|null $lastUsed
     */
    public function setLastUsed(?\DateTime $lastUsed): void
    {
        $this->lastUsed = $lastUsed;
    }

    /**
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived(bool $archived): void
    {
        $this->archived = $archived;
    }

    /**
     * @return float|null
     */
    public function getBudgetBilled(): ?float
    {
        return $this->budgetBilled;
    }

    /**
     * @param float|null $budgetBilled
     */
    public function setBudgetBilled(?float $budgetBilled): void
    {
        $this->budgetBilled = $budgetBilled;
    }
}
