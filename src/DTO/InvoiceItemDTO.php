<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Unit;
use App\Entity\VatRate;
use Symfony\Component\Validator\Constraints as Assert;

class InvoiceItemDTO
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     */
    public $amount;

    /**
     * @var Unit|null
     *
     * @Assert\NotNull()
     */
    public $unit;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     */
    public $priceSingle;

    /**
     * @var VatRate|null
     *
     * @Assert\NotNull()
     */
    public $vatRate;
}
