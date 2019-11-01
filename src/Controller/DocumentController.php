<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\DocumentDTO;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\User;
use App\Form\DocumentType;
use App\Helper\CurrentCompanyHelper;
use App\Manager\DocumentManager;
use App\Normalizer\DocumentNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('document/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        DocumentNormalizer $documentNormalizer,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

        /** @var Document[] $documents */
        $documents = $entityManager->getRepository(Document::class)->findBy(['company' => $company]);

        $response = [
            'data' => [],
        ];

        foreach ($documents as $document) {
            $response['data'][] = $documentNormalizer->normalize($document);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        DocumentManager $documentManager,
        EntityManagerInterface $entityManager,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

        /** @var User $user */
        $user = $this->getUser();

        $documentDTO = new DocumentDTO();
        $documentDTO->company = $company;
        $documentDTO->date = new \DateTime();

        $form = $this->createForm(DocumentType::class, $documentDTO, ['file_required' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->add($documentDTO, $user);

            $entityManager->flush();

            return $this->redirectToRoute('app_document_index');
        }

        return $this->render('document/form.html.twig', [
            'title' => 'Add Document',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Document $document,
        DocumentManager $documentManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $documentDTO = $documentManager->getEdit($document);

        $form = $this->createForm(DocumentType::class, $documentDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentManager->edit($document, $documentDTO, $user);

            return $this->redirectToRoute('app_document_index');
        }

        return $this->render('document/form.html.twig', [
            'title' => 'Edit Document',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Document $document,
        DocumentManager $documentManager
    ): Response {
        $documentManager->delete($document);

        return $this->redirectToRoute('app_document_index');
    }

    /**
     * @Route("/{id}/file/{filename}")
     */
    public function fileAction(
        Document $document
    ): Response {
        if ($document->getFileContents() === null ||
            $document->getFileMime() === null ||
            $document->getFileName() === null
        ) {
            throw $this->createNotFoundException();
        }

        $response = new Response();
        $response->headers->set('Content-Type', $document->getFileMime());

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $document->getFilename()
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->setContent($document->getFileContents());

        return $response;
    }
}
