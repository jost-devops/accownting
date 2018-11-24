<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Unit;
use App\Entity\VatRate;

class InvoiceLineItemDTO
{
    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string|null
     */
    public $description;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var Unit|null
     */
    public $unit;

    /**
     * @var float
     */
    public $priceSingle;

    /**
     * @var VatRate|null
     */
    public $vatRate;
}
