<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function getActiveCount(): int
    {
        return (int) $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.archived = false')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
