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

    /**
     * Transform given entity to array
     *
     * @param StatisticalRecord $record
     *
     * @return array
    */
    public static function transformRecord(StatisticalRecord $record): Array
    {
        return [
                'id'    => (int) $record->getId(),
                'timestamp' => date_format($record->getTimestamp(), 'd-m-Y H:i'),
                'referrer' => (string) $record->getReferrer(),
                'ip' => (string) $record->getIp(),
                'browser' => (string) $record->getBrowser()         
        ];
    }

}
