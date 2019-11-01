<?php declare(strict_types=1);

namespace App\Controller;

use App\Calculator\ProjectRangeCalculator;
use App\Calculator\ProjectVolumeCalculator;
use App\Calculator\SalesCalculator;
use App\Entity\Company;
use App\Entity\Project;
use App\Helper\CurrentCompanyHelper;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction(
        CurrentCompanyHelper $currentCompanyHelper,
        EntityManagerInterface $entityManager,
        SalesCalculator $salesCalculator,
        ProjectVolumeCalculator $projectVolumeCalculator,
        ProjectRangeCalculator $projectRangeCalculator
    ): Response {
        $salesLastYear = 0;
        $projectsActive = 0;
        $projectVolume = 0;
        $projectRange = 0;

        $currentCompany = $currentCompanyHelper->get();

        if ($currentCompany !== null) {
            $begin = (new \DateTime())->sub(new \DateInterval('P1Y'))->setTime(0, 0);
            $end = (new \DateTime());

            /** @var ProjectRepository $projectRepository */
            $projectRepository = $entityManager->getRepository(Project::class);

            $salesLastYear = $salesCalculator->calculate($currentCompany, $begin, $end);
            $projectsActive = $projectRepository->getActiveCount($currentCompany);
            $projectVolume = $projectVolumeCalculator->calculate($currentCompany);
            $projectRange = $projectRangeCalculator->calculate($currentCompany);
        }

        return $this->render('dashboard/index.html.twig', [
            'salesLastYear' => $salesLastYear,
            'projectsActive' => $projectsActive,
            'projectVolume' => $projectVolume,
            'projectRange' => $projectRange
        ]);
    }

    /**
     * @Route("/switch-company", methods={"POST"})
     */
    public function switchCompanyAction(
        Request $request,
        EntityManagerInterface $entityManager,
        CurrentCompanyHelper $currentCompanyHelper
    ): Response {
        /** @var Company|null $company */
        $company = $entityManager->getRepository(Company::class)->findOneBy(['id' => $request->get('company')]);
        $currentCompanyHelper->set($company);

        return $this->redirectToRoute('app_dashboard_index');
    }
}
