<?php

namespace App\Repository;

use App\Entity\DevisRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DevisRequest>
 */
class DevisRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DevisRequest::class);
    }

    public function countThisMonth(): int
    {
        $start = new \DateTimeImmutable('first day of this month midnight');

        return (int) $this->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->where('d.createdAt >= :start')
            ->setParameter('start', $start)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMostRequestedOffer(): ?string
    {
        $result = $this->createQueryBuilder('d')
            ->select('d.suggestedOffer, COUNT(d.id) as cnt')
            ->groupBy('d.suggestedOffer')
            ->orderBy('cnt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['suggestedOffer'] : null;
    }
}
