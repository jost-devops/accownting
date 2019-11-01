<?php declare(strict_types=1);

namespace App\Helper;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CurrentCompanyHelper
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function get(): ?Company
    {
        return $this->session->get('currentCompany');
    }

    public function set(?Company $company): void
    {
        $this->session->set('currentCompany', $company);
    }
}
