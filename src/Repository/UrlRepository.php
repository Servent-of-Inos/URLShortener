<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Url|null find($id, $lockMode = null, $lockVersion = null)
 * @method Url|null findOneBy(array $criteria, array $orderBy = null)
 * @method Url[]    findAll()
 * @method Url[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
        return [
                'id'    => (int) $url->getId(),
                'long_url' => (string) $url->getLongUrl(),
                'short_url' => (string) $url->getShortUrl(),
                'lifetime' => date_format($url->getLifetime(), 'd-m-Y H:i:s'),
                'is_active' => (boolean) $url->getIsActive()
        ];
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
