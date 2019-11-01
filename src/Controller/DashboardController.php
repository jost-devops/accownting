<?php declare(strict_types=1);

namespace App\Controller;

use App\Calculator\ProjectRangeCalculator;
use App\Calculator\ProjectVolumeCalculator;
use App\Calculator\SalesCalculator;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @return Response
     *
     * @Route("/")
     */
    public function indexAction(
        EntityManagerInterface $entityManager,
        SalesCalculator $salesCalculator,
        ProjectVolumeCalculator $projectVolumeCalculator,
        ProjectRangeCalculator $projectRangeCalculator
    ): Response {
        $begin = (new \DateTime())->sub(new \DateInterval('P1Y'))->setTime(0, 0);
        $end = (new \DateTime());

        /** @var ProjectRepository $projectRepository */
        $projectRepository = $entityManager->getRepository(Project::class);

        $salesLastYear = $salesCalculator->calculate($begin, $end);
        $projectsActive = $projectRepository->getActiveCount();
        $projectVolume = $projectVolumeCalculator->calculate();
        $projectRange = $projectRangeCalculator->calculate();

        return $this->render('dashboard/index.html.twig', [
            'salesLastYear' => $salesLastYear,
            'projectsActive' => $projectsActive,
            'projectVolume' => $projectVolume,
            'projectRange' => $projectRange
        ]);
    }
}
