<?php declare(strict_types=1);

namespace App\Repository;

use App\DTO\TimeTrackingExportDTO;
use App\Entity\Company;
use App\Entity\Project;
use App\Entity\TimeTrackItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TimeTrackingItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeTrackItem::class);
    }

    public function findByDate(Company $company, \DateTime $date): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.project', 'p')
            ->where('i.moment BETWEEN :start AND :end')
            ->setParameter('start', (clone $date)->setTime(0, 0))
            ->setParameter('end', (clone $date)->setTime(23, 59, 59))
            ->andWhere('p.company = :company')
            ->setParameter('company', $company)
            ->orderBy('i.moment', 'ASC')
            ->addOrderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findDurationByDate(Company $company, \DateTime $date): float
    {
        return (float)$this->createQueryBuilder('i')
            ->select('SUM(i.duration)')
            ->join('i.project', 'p')
            ->where('i.moment BETWEEN :start AND :end')
            ->setParameter('start', (clone $date)->setTime(0, 0))
            ->setParameter('end', (clone $date)->setTime(23, 59, 59))
            ->andWhere('p.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getSingleScalarResult()
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

    public function findByProjectForExport(Project $project, TimeTrackingExportDTO $timeTrackingExportDTO): array
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->where('i.project = :project')
            ->setParameter('project', $project)
            ->andWhere('i.moment BETWEEN :begin AND :end')
            ->setParameter('begin', $timeTrackingExportDTO->begin)
            ->setParameter('end', $timeTrackingExportDTO->end)
            ->orderBy('i.moment', 'ASC')
        ;

        if (!$timeTrackingExportDTO->includeNonChargeable) {
            $queryBuilder
                ->andWhere('i.chargeable = true');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
