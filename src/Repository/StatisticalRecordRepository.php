<?php

namespace App\Repository;

use App\Entity\StatisticalRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StatisticalRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatisticalRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatisticalRecord[]    findAll()
 * @method StatisticalRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatisticalRecordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StatisticalRecord::class);
    }

//    /**
//     * @return StatisticalRecord[] Returns an array of StatisticalRecord objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StatisticalRecord
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
