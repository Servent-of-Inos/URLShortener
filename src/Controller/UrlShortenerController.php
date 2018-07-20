<?php

namespace App\Controller;

use App\Entity\{Url, StatisticalRecord};
use App\Repository\UrlRepository;
use App\BijectiveFunction\Bijective;
use App\DatetimeChecker\DatetimeChecker;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};

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
     * @Route("/urls", name="urls", methods={"GET"})
    */
    public function index(UrlRepository $urlRepository)
    {
        $urls = $urlRepository->transformAll();

        return new JsonResponse($urls);

    }

    /**
     * @Route("/{slug}", name="show", methods={"GET"})
    */
    public function show(String $slug, UrlRepository $urlRepository, EntityManagerInterface $entityManager, Bijective $bjf, Request $request)
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
     * @Route("/add-url", name="add-url", methods={"POST"})
    */
    public function store(Request $request, UrlRepository $urlRepository, EntityManagerInterface $entityManager, Bijective $bjf)
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

        $url->setIsActive($request['is_active']);

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
     * @Route("/urls/{id}/edit-is-active", name="edit-is-active", methods={"PUT"})
    */
    public function update(Int $id, Request $request, EntityManagerInterface $entityManager, UrlRepository $urlRepository)
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