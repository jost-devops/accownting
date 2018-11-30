<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use App\Entity\TimeTrackItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TimeTrackingItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TimeTrackItem::class);
    }

    public function findByDate(\DateTime $date): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.moment BETWEEN :start AND :end')
            ->setParameter('start', (clone $date)->setTime(0, 0))
            ->setParameter('end', (clone $date)->setTime(23, 59, 59))
            ->orderBy('i.moment', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOpenByProject(Project $project): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.project = :project')
            ->setParameter('project', $project)
            ->andWhere('i.cleared = false')
            ->orderBy('i.moment', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMultipleByIds(array $ids): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('i.moment', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
