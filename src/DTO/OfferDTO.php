<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Company;
use App\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

class OfferDTO
{
    /**
     * @var Company|null
     *
     * @Assert\NotNull()
     */
    public $company;

    /**
     * @var Customer|null
     *
     * @Assert\NotNull()
     */
    public $customer;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $offerNumber;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     */
    public $offerDate;

    /**
     * @var OfferItemDTO[]
     *
     * @Assert\Valid()
     */
    public $items;

    /**
     * @var string
     */
    public $country;

    public function __construct()
    {
        $this->offerDate = new \DateTime();
    }
}