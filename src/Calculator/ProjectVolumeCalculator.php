<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

class ProjectVolumeCalculator
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculate()
    {
        /** @var Project[] $projects */
        $projects = $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(Project::class, 'p')
            ->where('p.archived = 0')
            ->andWhere('p.budget > 0')
            ->getQuery()
            ->getResult();

        $projectVolume = 0;

        foreach ($projects as $project) {
            $projectVolume += $project->getBudget();
        }

        return $projectVolume;
    }
}
