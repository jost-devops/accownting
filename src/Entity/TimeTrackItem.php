<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeTrackingItemRepository")
 */
class TimeTrackItem
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
     * @var Project|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $project;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $person;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $moment;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $duration;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $chargeable;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": "0"})
     */
    private $cleared = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     */
    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return User|null
     */
    public function getPerson(): ?User
    {
        return $this->person;
    }

    /**
     * @param User|null $person
     */
    public function setPerson(?User $person): void
    {
        $this->person = $person;
    }

    /**
     * @return \DateTime
     */
    public function getMoment(): \DateTime
    {
        return $this->moment;
    }

    /**
     * @param \DateTime $moment
     */
    public function setMoment(\DateTime $moment): void
    {
        $this->moment = $moment;
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration(float $duration): void
    {
        $this->duration = $duration;
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
     * @return bool
     */
    public function isChargeable(): bool
    {
        return $this->chargeable;
    }

    /**
     * @param bool $chargeable
     */
    public function setChargeable(bool $chargeable): void
    {
        $this->chargeable = $chargeable;
    }

    /**
     * @return bool
     */
    public function isCleared(): bool
    {
        return $this->cleared;
    }

    /**
     * @param bool $cleared
     */
    public function setCleared(bool $cleared): void
    {
        $this->cleared = $cleared;
    }
}
