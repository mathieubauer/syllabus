<?php

namespace App\Repository;

use App\Entity\LearningObjective;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LearningObjective|null find($id, $lockMode = null, $lockVersion = null)
 * @method LearningObjective|null findOneBy(array $criteria, array $orderBy = null)
 * @method LearningObjective[]    findAll()
 * @method LearningObjective[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LearningObjectiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LearningObjective::class);
    }

    // /**
    //  * @return LearningObjective[] Returns an array of LearningObjective objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LearningObjective
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
