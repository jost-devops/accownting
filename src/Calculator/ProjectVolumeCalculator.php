<?php declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Company;
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

    public function calculate(Company $company)
    {
        /** @var Project[] $projects */
        $projects = $this->entityManager
            ->createQueryBuilder()
            ->select('p')
            ->from(Project::class, 'p')
            ->where('p.archived = 0')
            ->andWhere('p.budget > 0')
            ->andWhere('p.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();

        $projectVolume = 0;

        foreach ($projects as $project) {
            $projectVolume += $project->getBudget() - ($project->getBudgetBilled() ?: 0);
        }

        return $projectVolume;
    }
}
