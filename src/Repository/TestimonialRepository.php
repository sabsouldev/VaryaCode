<?php

namespace App\Repository;

use App\Entity\Testimonial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testimonial>
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    /** @return Testimonial[] */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isPublished = true')
            ->orderBy('t.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
