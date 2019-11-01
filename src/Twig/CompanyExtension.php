<?php

namespace App\Twig;

use App\Entity\Company;
use App\Helper\CurrentCompanyHelper;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CompanyExtension extends AbstractExtension
{
    /** @var CurrentCompanyHelper */
    private $currentCompanyHelper;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(CurrentCompanyHelper $currentCompanyHelper, EntityManagerInterface $entityManager)
    {
        $this->currentCompanyHelper = $currentCompanyHelper;
        $this->entityManager = $entityManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'currentCompany',
                [$this, 'getCurrentCompany']
            ),
            new TwigFunction(
                'companies',
                [$this, 'getCompanies']
            ),
        ];
    }

    public function getCompanies(): array
    {
        $companies = $this->entityManager
            ->getRepository(Company::class)
            ->findAll();

        return $companies;
    }

    public function getCurrentCompany(): ?Company
    {
        return $this->currentCompanyHelper->get();
    }
}
