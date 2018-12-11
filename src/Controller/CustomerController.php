<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CustomerDTO;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Manager\CustomerManager;
use App\Normalizer\CustomerNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('customer/index.html.twig');
    }

    /**
     * @Route("/data")
     */
    public function dataAction(
        EntityManagerInterface $entityManager,
        CustomerNormalizer $customerNormalizer
    ): Response {
        /** @var Customer[] $customers */
        $customers = $entityManager->getRepository(Customer::class)->findAll();

        $response = [
            'data' => [],
        ];

        foreach ($customers as $customer) {
            $response['data'][] = $customerNormalizer->normalize($customer);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/add")
     */
    public function addAction(
        Request $request,
        CustomerManager $customerManager,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $customerDTO = new CustomerDTO();

        $form = $this->createForm(CustomerType::class, $customerDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $customerManager->add($customerDTO, $user);

            if ($customer->getCompany() !== null) {
                if ($customer->getCompany()->getNextCustomerNumber() === $customer->getCustomerNumber()) {
                    $customer->getCompany()->setNextCustomerNumber($customer->getCustomerNumber() + 1);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_customer_index');
        }

        return $this->render('customer/form.html.twig', [
            'title' => 'Add Customer',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(
        Request $request,
        Customer $customer,
        CustomerManager $customerManager
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $customerDTO = $customerManager->getEdit($customer);

        $form = $this->createForm(CustomerType::class, $customerDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerManager->edit($customer, $customerDTO, $user);

            return $this->redirectToRoute('app_customer_index');
        }

        return $this->render('customer/form.html.twig', [
            'title' => 'Edit Customer',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(
        Customer $customer,
        CustomerManager $customerManager
    ): Response {
        $customerManager->delete($customer);

        return $this->redirectToRoute('app_customer_index');
    }
}
