<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Document
{
    use CreatedTrait, UpdatedTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Company|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company")
     */
    private $company;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @var resource|string|null
     *
     * @ORM\Column(type="blob")
     */
    private $fileContents;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $fileMime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     */
    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    /**
     * @return resource|string|null
     */
    public function getFileContents()
    {
        if (is_resource($this->fileContents)) {
            $fileContents = stream_get_contents($this->fileContents);

            $this->fileContents = ($fileContents === false) ? null : $fileContents;
        }

        return $this->fileContents;
    }

    /**
     * @param resource|string $fileContents
     */
    public function setFileContents($fileContents): void
    {
        $this->fileContents = $fileContents;
    }

    /**
     * @return string|null
     */
    public function getFileMime(): ?string
    {
        return $this->fileMime;
    }

    /**
     * @param string $fileMime
     */
    public function setFileMime(string $fileMime): void
    {
        $this->fileMime = $fileMime;
    }
}
