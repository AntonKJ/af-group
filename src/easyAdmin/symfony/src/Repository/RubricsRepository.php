<?php

namespace App\Repository;

use App\Entity\Rubrics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rubrics|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rubrics|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rubrics[]    findAll()
 * @method Rubrics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RubricsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rubrics::class);
    }

    // /**
    //  * @return Rubrics[] Returns an array of Rubrics objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Rubrics
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
