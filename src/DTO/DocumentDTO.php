<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentDTO
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
     * @var \DateTime
     */
    public $date;

    /**
     * @var string
     */
    public $title;

    /**
     * @var UploadedFile
     */
    public $file;
}
