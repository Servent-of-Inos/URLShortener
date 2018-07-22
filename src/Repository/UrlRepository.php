<?php

namespace App\Repository;

use App\Entity\Url;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class UrlRepository extends ServiceEntityRepository
{   
    /**
     * @var integer Url per page displayed
    */
    protected $maxPerPage = 3;

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
    public function transform(Url $url): Array
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
                    'lifetime' => ($url->getLifetime() !== NULL) ? date_format($url->getLifetime(), 'd-m-Y H:i') : NULL,
                    'is_active' => (boolean) $url->getIsActive(),
                    'statistics' => (array) $recordsArray
            ];

        } else {

            return [
                    'id'    => (int) $url->getId(),
                    'long_url' => (string) $url->getLongUrl(),
                    'short_url' => (string) $url->getShortUrl(),
                    'lifetime' => ($url->getLifetime() !== NULL) ? date_format($url->getLifetime(), 'd-m-Y H:i') : NULL,
                    'is_active' => (boolean) $url->getIsActive()
            ];

        }
    }

    /**
     * Transform given entity collection to array
     *
     * @return array
    */
    public function transformAll(): Array
    {
        $urls = $this->findAll();
        $urlsArray = [];

        foreach ($urls as $url) {
            $urlsArray[] = $this->transform($url);
        }

        return $urlsArray;
    }

    /**
     * Create pagination instance
     *
     * @return object
    */
    public function createPaginator(array $urls, int $page): Array
    {
        $paginator = new Pagerfanta(new ArrayAdapter($urls));
        $total = $paginator->getNbResults();
        $paginator->setMaxPerPage($this->maxPerPage);
        $paginator->setCurrentPage($page);

        return [

            'paginator' => $paginator,
            'total' => $total

        ];
    }
}
