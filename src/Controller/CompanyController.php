<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CompanyDTO;
use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Manager\CompanyManager;
use App\Normalizer\CompanyNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('company/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        CompanyNormalizer $companyNormalizer
    ): Response {
        /** @var Company[] $companies */
        $companies = $entityManager->getRepository(Company::class)->findAll();

        $response = [
            'data' => [],
        ];

        foreach ($companies as $company) {
            $response['data'][] = $companyNormalizer->normalize($company);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        CompanyManager $companyManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $companyDTO = new CompanyDTO();

        $form = $this->createForm(CompanyType::class, $companyDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyManager->add($companyDTO, $user);

            return $this->redirectToRoute('app_company_index');
        }

        return $this->render('company/form.html.twig', [
            'title' => 'Add Company',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Company $company,
        CompanyManager $companyManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $companyDTO = $companyManager->getEdit($company);

        $form = $this->createForm(CompanyType::class, $companyDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyManager->edit($company, $companyDTO, $user);

            return $this->redirectToRoute('app_company_index');
        }

        return $this->render('company/form.html.twig', [
            'title' => 'Edit Company',
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Company $company,
        CompanyManager $companyManager
    ): Response {
        $companyManager->delete($company);

        return $this->redirectToRoute('app_company_index');
    }

    /**
     * @Route("/{id}/logo")
     */
    public function logoAction(
        Company $company
    ): Response {
        if ($company->getLogo() === null || $company->getLogoMime() === null) {
            throw $this->createNotFoundException();
        }

        $mimeTypeExtensionGuesser = new MimeTypeExtensionGuesser();

        $response = new Response();
        $response->headers->set('Content-Type', $company->getLogoMime());
        $response->headers->set(
            'Content-Disposition',
            ResponseHeaderBag::DISPOSITION_ATTACHMENT . ', filename="' . $company->getId() . '.' .
            $mimeTypeExtensionGuesser->guess($company->getLogoMime()) . '"'
        );
        $response->setContent($company->getLogo());

        return $response;
    }

    /**
     * @Route("/{id}/next-numbers")
     */
    public function getNextNumbers(Company $company): Response
    {
        return new JsonResponse([
            'success' => true,
            'numbers' => [
                'nextInvoiceNumber' => $company->getNextInvoiceNumber(),
                'nextOfferNumber' => $company->getNextOfferNumber(),
                'nextCustomerNumber' => $company->getNextCustomerNumber(),
                'nextProjectNumber' => $company->getNextProjectNumber(),
            ]
        ]);
    }
}
