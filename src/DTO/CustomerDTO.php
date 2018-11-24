<?php declare(strict_types=1);

namespace App\DTO;

class CustomerDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $additionalName;

    /**
     * @var string|null
     */
    public $street;

    /**
     * @var string|null
     */
    public $zip;

    /**
     * @var string|null
     */
    public $city;

    /**
     * @var string|null
     */
    public $country;
}
