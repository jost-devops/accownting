<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\CustomerDTO;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CustomerManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(CustomerDTO $customerDTO, User $user): Customer
    {
        $customer = new Customer();
        $customer->setCompany($customerDTO->company);
        $customer->setName($customerDTO->name);
        $customer->setAdditionalName($customerDTO->additionalName);
        $customer->setStreet($customerDTO->street);
        $customer->setZip($customerDTO->zip);
        $customer->setCity($customerDTO->city);
        $customer->setCountry($customerDTO->country);
        $customer->setCreated(new \DateTime());
        $customer->setCreatedBy($user);

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $customer;
    }

    public function getEdit(Customer $customer): CustomerDTO
    {
        $customerDTO = new CustomerDTO();
        $customerDTO->company = $customer->getCompany();
        $customerDTO->name = $customer->getName();
        $customerDTO->additionalName = $customer->getAdditionalName();
        $customerDTO->street = $customer->getStreet();
        $customerDTO->zip = $customer->getZip();
        $customerDTO->city = $customer->getCity();
        $customerDTO->country = $customer->getCountry();

        return $customerDTO;
    }

    public function edit(Customer $customer, CustomerDTO $customerDTO, User $user): Customer
    {
        $customer->setCompany($customerDTO->company);
        $customer->setName($customerDTO->name);
        $customer->setAdditionalName($customerDTO->additionalName);
        $customer->setStreet($customerDTO->street);
        $customer->setZip($customerDTO->zip);
        $customer->setCity($customerDTO->city);
        $customer->setCountry($customerDTO->country);
        $customer->setUpdated(new \DateTime());
        $customer->setUpdatedBy($user);

        $this->entityManager->flush();

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }
}
