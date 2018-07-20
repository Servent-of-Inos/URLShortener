<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UrlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Url::class);
    }

    /**
     * Transform given entity to array
     *
     * @param Url $url
     *
     * @return array
    */
    public function transform(Url $url)
    {
        $records = $url->getStatisticalrecord();

        foreach ($records as $record) {

            $recordsArray[] = StatisticalRecordRepository::transformRecord($record);

        }

        if(isset($recordsArray)) {

            return [
                    'id'    => (int) $url->getId(),
                    'long_url' => (string) $url->getLongUrl(),
                    'short_url' => (string) $url->getShortUrl(),
                    'lifetime' => date_format($url->getLifetime(), 'd-m-Y H:i'),
                    'is_active' => (boolean) $url->getIsActive(),
                    'statistics' => (array) $recordsArray
            ];

        } else {

            return [
                    'id'    => (int) $url->getId(),
                    'long_url' => (string) $url->getLongUrl(),
                    'short_url' => (string) $url->getShortUrl(),
                    'lifetime' => date_format($url->getLifetime(), 'd-m-Y H:i'),
                    'is_active' => (boolean) $url->getIsActive()
            ];

        }
    }

    /**
     * Transform given entity collection to array
     *
     * @return array
    */
    public function transformAll()
    {
        $urls = $this->findAll();
        $urlsArray = [];

        foreach ($urls as $url) {
            $urlsArray[] = $this->transform($url);
        }

        return $urlsArray;
    }

}
