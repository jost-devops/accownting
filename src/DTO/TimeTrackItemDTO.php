<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Project;
use Symfony\Component\Validator\Constraints as Assert;

class TimeTrackItemDTO
{
    /**
     * @var Project|null
     */
    public $project;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     */
    public $moment;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     */
    public $duration;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var bool
     */
    public $chargeable;
}
