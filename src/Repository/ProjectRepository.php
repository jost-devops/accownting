<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getActiveCount(Company $company): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.archived = false')
            ->andWhere('p.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
