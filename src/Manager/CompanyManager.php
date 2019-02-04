<?php declare(strict_types=1);

namespace App\Manager;

use App\DTO\CompanyDTO;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CompanyManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(CompanyDTO $companyDTO, User $user): Company
    {
        $company = new Company();
        $company->setName($companyDTO->name);
        $company->setAdditionalName($companyDTO->additionalName);
        $company->setStreet($companyDTO->street);
        $company->setZip($companyDTO->zip);
        $company->setCity($companyDTO->city);
        $company->setCountry($companyDTO->country);
        $company->setPhone($companyDTO->phone);
        $company->setFax($companyDTO->fax);
        $company->setWebsite($companyDTO->website);
        $company->setEmail($companyDTO->email);
        $company->setVatNumber($companyDTO->vatNumber);
        $company->setTaxNumber($companyDTO->taxNumber);
        $company->setDistrictCourt($companyDTO->districtCourt);
        $company->setCompanyRegisterId($companyDTO->companyRegisterId);
        $company->setTitleOfManagingDirector($companyDTO->titleOfManagingDirector);
        $company->setManagingDirector($companyDTO->managingDirector);
        $company->setBankName($companyDTO->bankName);
        $company->setBankIban($companyDTO->bankIban);
        $company->setBankBic($companyDTO->bankBic);
        $company->setNextCustomerNumber($companyDTO->nextCustomerNumber);
        $company->setNextOfferNumber($companyDTO->nextOfferNumber);
        $company->setNextInvoiceNumber($companyDTO->nextInvoiceNumber);
        $company->setNextProjectNumber($companyDTO->nextProjectNumber);
        $company->setCreated(new \DateTime());
        $company->setCreatedBy($user);

        $this->handleLogo($company, $companyDTO);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return $company;
    }

    public function getEdit(Company $company): CompanyDTO
    {
        $companyDTO = new CompanyDTO();
        $companyDTO->name = $company->getName();
        $companyDTO->additionalName = $company->getAdditionalName();
        $companyDTO->street = $company->getStreet();
        $companyDTO->zip = $company->getZip();
        $companyDTO->city = $company->getCity();
        $companyDTO->country = $company->getCountry();
        $companyDTO->phone = $company->getPhone();
        $companyDTO->fax = $company->getFax();
        $companyDTO->website = $company->getWebsite();
        $companyDTO->email = $company->getEmail();
        $companyDTO->vatNumber = $company->getVatNumber();
        $companyDTO->taxNumber = $company->getTaxNumber();
        $companyDTO->districtCourt = $company->getDistrictCourt();
        $companyDTO->companyRegisterId = $company->getCompanyRegisterId();
        $companyDTO->titleOfManagingDirector = $company->getTitleOfManagingDirector();
        $companyDTO->managingDirector = $company->getManagingDirector();
        $companyDTO->bankName = $company->getBankName();
        $companyDTO->bankIban = $company->getBankIban();
        $companyDTO->bankBic = $company->getBankBic();
        $companyDTO->nextCustomerNumber = $company->getNextCustomerNumber();
        $companyDTO->nextOfferNumber = $company->getNextOfferNumber();
        $companyDTO->nextInvoiceNumber = $company->getNextInvoiceNumber();
        $companyDTO->nextProjectNumber = $company->getNextProjectNumber();

        return $companyDTO;
    }

    public function edit(Company $company, CompanyDTO $companyDTO, User $user): Company
    {
        $company->setName($companyDTO->name);
        $company->setAdditionalName($companyDTO->additionalName);
        $company->setStreet($companyDTO->street);
        $company->setZip($companyDTO->zip);
        $company->setCity($companyDTO->city);
        $company->setCountry($companyDTO->country);
        $company->setPhone($companyDTO->phone);
        $company->setFax($companyDTO->fax);
        $company->setWebsite($companyDTO->website);
        $company->setEmail($companyDTO->email);
        $company->setVatNumber($companyDTO->vatNumber);
        $company->setTaxNumber($companyDTO->taxNumber);
        $company->setDistrictCourt($companyDTO->districtCourt);
        $company->setCompanyRegisterId($companyDTO->companyRegisterId);
        $company->setTitleOfManagingDirector($companyDTO->titleOfManagingDirector);
        $company->setManagingDirector($companyDTO->managingDirector);
        $company->setBankName($companyDTO->bankName);
        $company->setBankIban($companyDTO->bankIban);
        $company->setBankBic($companyDTO->bankBic);
        $company->setNextCustomerNumber($companyDTO->nextCustomerNumber);
        $company->setNextOfferNumber($companyDTO->nextOfferNumber);
        $company->setNextInvoiceNumber($companyDTO->nextInvoiceNumber);
        $company->setNextProjectNumber($companyDTO->nextProjectNumber);
        $company->setUpdated(new \DateTime());
        $company->setUpdatedBy($user);

        $this->handleLogo($company, $companyDTO);

        $this->entityManager->flush();

        return $company;
    }

    public function delete(Company $company): void
    {
        $this->entityManager->remove($company);
        $this->entityManager->flush();
    }

    private function handleLogo(Company $company, CompanyDTO $companyDTO): void
    {
        if ($companyDTO->logo !== null) {
            $logoContents = file_get_contents($companyDTO->logo->getPathname());

            if ($logoContents !== false && $companyDTO->logo->getMimeType() !== null) {
                $company->setLogo($logoContents);
                $company->setLogoMime($companyDTO->logo->getMimeType());
            }
        }
    }
}
