<?php declare(strict_types=1);

namespace App\Helper;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CurrentCompanyHelper
{
    /** @var SessionInterface */
    private $session;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function get(): ?Company
    {
        /** @var int|null $currentCompany */
        $currentCompany = $this->session->get('currentCompany');

        $company = null;

        if ($currentCompany !== null) {
            /** @var Company|null $company */
            $company = $this->entityManager->getRepository(Company::class)->findOneBy(['id' => $currentCompany]);
        }

        return $company;
    }

    public function set(?Company $company): void
    {
        $this->session->set('currentCompany', ($company !== null) ? $company->getId() : null);
    }
}
