<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TimeTrackItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TimeTrackingItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TimeTrackItem::class);
    }

    /**
     * @param \DateTime $date
     * @return array
     */
    public function findByDate(\DateTime $date): array
    {
        $queryBuilder = $this->createQueryBuilder('i')
            ->where('i.moment BETWEEN :start AND :end')
            ->setParameter('start', (clone $date)->setTime(0, 0))
            ->setParameter('end', (clone $date)->setTime(23, 59, 59));
        ;

        return $queryBuilder
            ->orderBy('i.moment', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
