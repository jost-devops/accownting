<?php declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyDTO
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

    /**
     * @var string|null
     */
    public $phone;

    /**
     * @var string|null
     */
    public $fax;

    /**
     * @var string|null
     */
    public $website;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var string|null
     */
    public $vatNumber;

    /**
     * @var string|null
     */
    public $taxNumber;

    /**
     * @var string|null
     */
    public $districtCourt;

    /**
     * @var string|null
     */
    public $companyRegisterId;

    /**
     * @var string|null
     */
    public $titleOfManagingDirector;

    /**
     * @var string|null
     */
    public $managingDirector;

    /**
     * @var string|null
     */
    public $bankName;

    /**
     * @var string|null
     */
    public $bankIban;

    /**
     * @var string|null
     */
    public $bankBic;

    /**
     * @var File|null
     */
    public $logo;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     */
    public $nextOfferNumber = 1;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     */
    public $nextInvoiceNumber = 1;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     */
    public $nextCustomerNumber = 1;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     */
    public $nextProjectNumber = 1;
}
