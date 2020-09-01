<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\OfferDTO;
use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\User;
use App\Form\OfferType;
use App\Generator\OfferGenerator;
use App\Helper\CurrentCompanyHelper;
use App\Manager\OfferManager;
use App\Normalizer\OfferNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/offer")
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('offer/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        OfferNormalizer $offerNormalizer,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

        /** @var Offer[] $offers */
        $offers = $entityManager
            ->getRepository(Offer::class)
            ->findBy(['company' => $company], ['offerDate' => 'DESC']);

        $response = [
            'data' => [],
        ];

        foreach ($offers as $offer) {
            $response['data'][] = $offerNormalizer->normalize($offer);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        OfferManager $offerManager,
        EntityManagerInterface $entityManager,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company $company */
        $company = $currentCompanyHelper->get();

        /** @var User $user */
        $user = $this->getUser();

        $offerDTO = new OfferDTO();
        $offerDTO->company = $company;
        $offerDTO->offerNumber = $company->getNextOfferNumber();

        $form = $this->createForm(OfferType::class, $offerDTO, ['company' => $company]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer = $offerManager->add($offerDTO, $user);

            if ($offer->getCompany() !== null) {
                if ($offer->getCompany()->getNextOfferNumber() === $offer->getOfferNumber()) {
                    $offer->getCompany()->setNextOfferNumber($offer->getOfferNumber() + 1);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_offer_index');
        }

        return $this->render('offer/form.html.twig', [
            'title' => 'New Offer',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Offer $offer,
        OfferManager $offerManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $offerDTO = $offerManager->getEdit($offer);

        $form = $this->createForm(OfferType::class, $offerDTO, ['company' => $offerDTO->company]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offerManager->edit($offer, $offerDTO, $user);

            return $this->redirectToRoute('app_offer_index');
        }

        return $this->render('offer/form.html.twig', [
            'title' => 'Edit Offer',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Offer $offer,
        OfferManager $offerManager
    ): Response {
        $offerManager->delete($offer);

        return $this->redirectToRoute('app_offer_index');
    }

    /**
     * @Route("/{id}/pdf")
     */
    public function pdfAction(
        Offer $offer,
        OfferGenerator $offerGenerator,
        TranslatorInterface $translator
    ): Response {
        $pdf = $offerGenerator->generate($offer);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set(
            'Content-Disposition',
            'inline; filename=' . $translator->trans('Offer') . '-' . $offer->getOfferNumber() . '.pdf'
        );
        $response->setContent($pdf);

        return $response;
    }

    /**
     * @Route("/{id}/duplicate")
     */
    public function duplicateAction(
        Offer $offer,
        OfferManager $offerManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $offerManager->duplicate($offer, $user);

        return $this->redirectToRoute('app_offer_index');
    }
}
