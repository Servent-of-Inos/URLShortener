<?php

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;
Use App\BijectiveFunction\Bijective;

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
     * @Route("/add-url", name="add-url", methods={"POST"})
    */
    public function store(Request $request, UrlRepository $urlRepository, EntityManagerInterface $entityManager)
    {
        $request = json_decode(
            $request->getContent(),
            true
        );

        if (!$request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (!$request['long_url']) {
            return $this->respondValidationError('Please provide url!');
        }

        $url = new Url;

        $url->setLongUrl($request['long_url']);

        if ($request['lifetime'] == '') {

            $url->setLifetime(NULL);

        } else {

            $url->setLifetime(new \DateTime($request['lifetime']));

        }

        if (isset($request['is_active'])) {

            $url->setIsActive($request['is_active']);

        } else {

            $url->setIsActive(false);

        }

        $entityManager->persist($url);

        $entityManager->flush();

        $bjf = new Bijective;

        $shortUrl = $bjf->encode($url->getId());

        $url->setShortUrl('http://loca.ly/'.$shortUrl);

        $entityManager->persist($url);

        $entityManager->flush();
        
        $this->statusCode=201;

        return new JsonResponse($request, $this->statusCode);

    }

}
