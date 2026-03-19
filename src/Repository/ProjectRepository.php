<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /** @return Project[] */
    public function findPublished(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isPublished = true')
            ->orderBy('p.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
