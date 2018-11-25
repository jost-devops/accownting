<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class TimeTrackItemDTO
{
    /**
     * @var Project|null
     */
    public $project;

    /**
     * @var User|null
     */
    public $person;

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
    public $chargeable = true;

    public function __construct()
    {
        $this->moment = new \DateTime();
    }
}
