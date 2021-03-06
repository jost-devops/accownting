<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Company;
use App\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceDTO
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
     * @var int
     *
     * @Assert\NotBlank()
     */
    public $invoiceNumber;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $subject;

    /**
     * @var string|null
     */
    public $text;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     */
    public $invoiceDate;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     */
    public $timeOfSupply;

    /**
     * @var \DateTime|null
     */
    public $timeOfSupplyEnd;

    /**
     * @var int|null
     */
    public $creditPeriod;

    /**
     * @var InvoiceItemDTO[]
     *
     * @Assert\Valid()
     */
    public $items;

    /**
     * @var \DateTime|null
     */
    public $paid;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $paymentMethod = 'bankwire';

    public function __construct()
    {
        $this->invoiceDate = new \DateTime();
        $this->timeOfSupply = new \DateTime();
    }
}
