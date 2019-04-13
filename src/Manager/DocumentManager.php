<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\DocumentDTO;
use App\Entity\Document;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DocumentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(DocumentDTO $documentDTO, User $user): Document
    {
        $document = new Document();
        $document->setCompany($documentDTO->company);
        $document->setDate($documentDTO->date);
        $document->setTitle($documentDTO->title);
        $document->setCreated(new \DateTime());
        $document->setCreatedBy($user);

        $this->handleFile($document, $documentDTO);

        $this->entityManager->persist($document);
        $this->entityManager->flush();

        return $document;
    }

    public function getEdit(Document $document): DocumentDTO
    {
        $documentDTO = new DocumentDTO();
        $documentDTO->company = $document->getCompany();
        $documentDTO->date = $document->getDate();
        $documentDTO->title = $document->getTitle();

        return $documentDTO;
    }

    public function edit(Document $document, DocumentDTO $documentDTO, User $user): Document
    {
        $document->setCompany($documentDTO->company);
        $document->setDate($documentDTO->date);
        $document->setTitle($documentDTO->title);
        $document->setUpdated(new \DateTime());
        $document->setUpdatedBy($user);

        $this->handleFile($document, $documentDTO);

        $this->entityManager->flush();

        return $document;
    }

    public function delete(Document $document): void
    {
        $this->entityManager->remove($document);
        $this->entityManager->flush();
    }

    private function handleFile(Document $document, DocumentDTO $documentDTO): void
    {
        if ($documentDTO->file !== null) {
            $fileContents = file_get_contents($documentDTO->file->getPathname());
            $fileMimeType = $documentDTO->file->getMimeType();
            $fileName = $documentDTO->file->getClientOriginalName();

            if ($fileContents !== false && $fileMimeType !== null && $fileName !== null) {
                $document->setFileName($fileName);
                $document->setFileMime($fileMimeType);
                $document->setFileContents($fileContents);
            }
        }
    }
}
