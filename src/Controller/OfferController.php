<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\InvoiceDTO;
use App\DTO\OfferDTO;
use App\Entity\Invoice;
use App\Entity\Offer;
use App\Entity\User;
use App\Form\InvoiceSetPaidType;
use App\Form\InvoiceType;
use App\Form\OfferType;
use App\Generator\InvoiceGenerator;
use App\Generator\OfferGenerator;
use App\Manager\InvoiceManager;
use App\Manager\OfferManager;
use App\Normalizer\InvoiceNormalizer;
use App\Normalizer\OfferNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/offer")
 */
class OfferController extends Controller
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
        OfferNormalizer $offerNormalizer
    ): Response {
        /** @var Offer[] $offers */
        $offers = $entityManager->getRepository(Offer::class)->findBy([], ['offerDate' => 'DESC']);

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
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $offerDTO = new OfferDTO();

        $form = $this->createForm(OfferType::class, $offerDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer = $offerManager->add($offerDTO, $user);

            if ($offer->getCompany()->getNextOfferNumber() === $offer->getOfferNumber()) {
                $offer->getCompany()->setNextOfferNumber($offer->getOfferNumber() + 1);
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

        $form = $this->createForm(OfferType::class, $offerDTO);
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
}
