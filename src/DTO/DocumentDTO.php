<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class DocumentDTO
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var string
     */
    public $title;

    /**
     * @var File
     */
    public $file;
}
