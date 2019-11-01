<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectRangeCalculator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SalesCalculator */
    private $salesCalculator;

    public function __construct(
        EntityManagerInterface $entityManager,
        SalesCalculator $salesCalculator
    ) {
        $this->entityManager = $entityManager;
        $this->salesCalculator = $salesCalculator;
    }

    public function calculate()
    {
        $begin = (new \DateTime())->sub(new \DateInterval('P1Y'))->setTime(0, 0);
        $end = (new \DateTime());

        $salesLastYear = $this->salesCalculator->calculate($begin, $end);

        /** @var Project[] $projects */
        $projects = $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(Project::class, 'p')
            ->where('p.archived = 0')
            ->andWhere('p.budget > 0')
            ->getQuery()
            ->getResult();

        $projectBudgetToBeBilled = 0;

        foreach ($projects as $project) {
            $projectBudgetToBeBilled += $project->getBudget() - ($project->getBudgetBilled() ?: 0);
        }

        if ($salesLastYear > 0) {
            $projectRange = $projectBudgetToBeBilled / $salesLastYear * 360;
        } else {
            $projectRange = 999999999;
        }


        return floor($projectRange);
    }
}
