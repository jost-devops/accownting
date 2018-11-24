<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Company;

class CustomerDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Company|null
     */
    public $company;

    /**
     * @var string
     */
    public $customerNumber;

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

    /**
     * @var string|null
     */
    public $salutation;

    /**
     * @var string|null
     */
    public $firstName;

    /**
     * @var string|null
     */
    public $lastName;
}
