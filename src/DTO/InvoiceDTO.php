<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Company;
use App\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceDTO
{
    /**
     * @var Company
     */
    public $company;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var string
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
     * @var InvoiceLineItemDTO[]
     *
     * @Assert\Valid()
     */
    public $lineItems;
}
