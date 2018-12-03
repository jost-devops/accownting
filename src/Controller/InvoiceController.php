<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\InvoiceDTO;
use App\Entity\Invoice;
use App\Entity\User;
use App\Form\InvoiceSetPaidType;
use App\Form\InvoiceType;
use App\Generator\InvoiceGenerator;
use App\Manager\InvoiceManager;
use App\Normalizer\InvoiceNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/invoice")
 */
class InvoiceController extends Controller
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('invoice/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        InvoiceNormalizer $invoiceNormalizer
    ): Response {
        /** @var Invoice[] $invoices */
        $invoices = $entityManager->getRepository(Invoice::class)->findBy([], ['invoiceDate' => 'DESC']);

        $response = [
            'data' => [],
        ];

        foreach ($invoices as $invoice) {
            $response['data'][] = $invoiceNormalizer->normalize($invoice);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        InvoiceManager $invoiceManager,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $invoiceDTO = new InvoiceDTO();

        $form = $this->createForm(InvoiceType::class, $invoiceDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoice = $invoiceManager->add($invoiceDTO, $user);

            if ($invoice->getCompany()->getNextInvoiceNumber() === $invoice->getInvoiceNumber()) {
                $invoice->getCompany()->setNextInvoiceNumber($invoice->getInvoiceNumber() + 1);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_index');
        }

        return $this->render('invoice/form.html.twig', [
            'title' => 'New Invoice',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Invoice $invoice,
        InvoiceManager $invoiceManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $invoiceDTO = $invoiceManager->getEdit($invoice);

        $form = $this->createForm(InvoiceType::class, $invoiceDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceManager->edit($invoice, $invoiceDTO, $user);

            return $this->redirectToRoute('app_invoice_index');
        }

        return $this->render('invoice/form.html.twig', [
            'title' => 'Edit Invoice',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Invoice $invoice,
        InvoiceManager $invoiceManager
    ): Response {
        $invoiceManager->delete($invoice);

        return $this->redirectToRoute('app_invoice_index');
    }

    /**
     * @Route("/{id}/set-paid")
     */
    public function setPaidAction(
        Request $request,
        Invoice $invoice,
        InvoiceManager $invoiceManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $invoiceDTO = $invoiceManager->getEdit($invoice);

        $form = $this->createForm(InvoiceSetPaidType::class, $invoiceDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceManager->edit($invoice, $invoiceDTO, $user);

            return $this->redirectToRoute('app_invoice_index');
        }

        return $this->render('invoice/set-paid.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/pdf")
     */
    public function pdfAction(
        Invoice $invoice,
        InvoiceGenerator $invoiceGenerator,
        TranslatorInterface $translator
    ): Response {
        $pdf = $invoiceGenerator->generate($invoice);

        $response = new Response();
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set(
            'Content-Disposition',
            'inline; filename=' . $translator->trans('Invoice') . '-' . $invoice->getInvoiceNumber() . '.pdf'
        );
        $response->setContent($pdf);

        return $response;
    }
}
