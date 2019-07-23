<?php declare(strict_types=1);

namespace App\DTO;

class TimeTrackingExportDTO
{
    /**
     * @var \DateTime
     */
    public $begin;

    /**
     * @var \DateTime
     */
    public $end;

    /**
     * @var bool
     */
    public $includeNonChargeable = true;
}
