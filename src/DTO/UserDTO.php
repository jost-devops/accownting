<?php declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string|null
     */
    public $name;
}
