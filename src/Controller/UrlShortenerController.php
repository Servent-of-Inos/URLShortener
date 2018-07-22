<?php

namespace App\Controller;

use App\Entity\{Url, StatisticalRecord};
use App\Repository\UrlRepository;
use App\BijectiveFunction\Bijective;
use App\DatetimeChecker\DatetimeChecker;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityManagerInterface;

class UrlShortenerController extends Controller
{
    /**
     * @var integer HTTP status code - 200 (OK) by default
    */
    protected $statusCode = 200;

    /**
     * @Route("/", name="index", methods={"GET"})
    */
    public function view()
    {
        return $this->render('url_shortener/index.html.twig');
    }

    /**
     * @Route("/urls", defaults={"page": "1"}, methods={"GET"}, name="urls_index")
     * @Route("/urls/{page}", requirements={"page": "[1-9]\d*"}, methods={"GET"}, name="urls_index_paginated")
    */
    public function index(int $page, UrlRepository $urlRepository): JsonResponse
    {
        $urls = $urlRepository->transformAll();

        $paginator = $urlRepository->createPaginator($urls, $page);

        $totalUrls =  $paginator['total'];

        $urlsChunk = $paginator['paginator']->getCurrentPageResults();

        return new JsonResponse([

            'urls' => $urlsChunk,
            'totalUrls' => $totalUrls

        ]);

    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
    */
    public function show(String $slug, UrlRepository $urlRepository, EntityManagerInterface $entityManager, Bijective $bjf, Request $request): Response
    {
        $id = $bjf->decode($slug);

        $url = $urlRepository->find($id);

        if (!$url) {

            $message='Not found!';

            return $this->render('url_shortener/partials/404.html.twig', array(
            'message' => $message));
            
        }

        if (DatetimeChecker::isExpire($url->getLifetime())){

            $message='It seems the lifetime of this link has expired!';

            return $this->render('url_shortener/partials/404.html.twig', array(
            'message' => $message));

        }

        if ($url->getIsActive()) {

            $timestamp = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $referer = $request->server->get('HTTP_REFERER');
            $ip = $request->server->get('REMOTE_ADDR');
            $browser = $request->server->get('HTTP_USER_AGENT');

            $record = new StatisticalRecord;

            $record->setUrl($url);
            $record->setTimestamp($timestamp);
            $record->setReferrer($referer);
            $record->setIp($ip);
            $record->setBrowser($browser);

            $entityManager->persist($record);

            $url->addStatisticalrecord($record);

            $entityManager->persist($url);
            $entityManager->flush();

            $url = $url->getLongUrl();

            // redirects externally
            return $this->redirect($url);

        } else {

            $message='It seems the link you want to access is not active! You can activate it on the main page.';

            return $this->render('url_shortener/partials/404.html.twig', array(
            'message' => $message));

        }

    }

    /**
     * @Route("/add-url", methods={"POST"}, name="add-url")
    */
    public function store(Request $request, UrlRepository $urlRepository, EntityManagerInterface $entityManager, Bijective $bjf): JsonResponse
    {
        $request = json_decode(
            $request->getContent(),
            true
        );

        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        $url = $urlRepository->findOneBy(array('long_url' => $request['long_url']));

        if (isset($url)) {

            $this->statusCode=419;

            $message = json_encode(array('message' => 'This url already in database!'));

            return new JsonResponse($message, $this->statusCode);

        }

        if (!$request['long_url']) {
            return $this->respondValidationError('Please provide url!');
        }

        $url = new Url;

        $url->setLongUrl($request['long_url']);

        if (!isset($request['lifetime'])) {

            $url->setLifetime(NULL);

        } else {

            $url->setLifetime(new \DateTime($request['lifetime']));

        }

        // temporary if statement -  need to fix that bug, something weird in front-end checkbox=((((
        if (isset($request['is_active'])) {

            $url->setIsActive($request['is_active']);

        } else {

            $url->setIsActive(false);

        }

        $entityManager->persist($url);

        $entityManager->flush();

        $shortUrl = $bjf->encode($url->getId());

        $url->setShortUrl('http://loca.ly/'.$shortUrl);

        $entityManager->persist($url);

        $entityManager->flush();
        
        $this->statusCode=201;

        return new JsonResponse($urlRepository->transform($url), $this->statusCode);

    }

    /**
     * @Route("/urls/{id}/edit-is-active", methods={"PUT"}, name="edit-is-active")
    */
    public function update(Int $id, Request $request, EntityManagerInterface $entityManager, UrlRepository $urlRepository): JsonResponse
    {
        $request = json_decode(
            $request->getContent(),
            true
        );

        $url = $urlRepository->find($id);

        $url->setIsActive($request['is_active']);

        $entityManager->persist($url);

        $entityManager->flush();

        $this->statusCode=201;

        return new JsonResponse($request, $this->statusCode);
    }

}