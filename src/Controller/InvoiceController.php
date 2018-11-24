<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\InvoiceDTO;
use App\DTO\VatRateDTO;
use App\Entity\Invoice;
use App\Entity\User;
use App\Entity\VatRate;
use App\Form\InvoiceType;
use App\Form\VatRateType;
use App\Manager\InvoiceManager;
use App\Manager\VatRateManager;
use App\Normalizer\InvoiceNormalizer;
use App\Normalizer\VatRateNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        InvoiceManager $invoiceManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $invoiceDTO = new InvoiceDTO();

        $form = $this->createForm(InvoiceType::class, $invoiceDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceManager->add($invoiceDTO, $user);

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
}
