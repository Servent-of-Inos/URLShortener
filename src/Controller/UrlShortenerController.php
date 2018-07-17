<?php

namespace App\Controller;

use App\Entity\Url;
use App\Repository\UrlRepository;

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
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
    */
    public function index()
    {
        return $this->render('url_shortener/index.html.twig');
    }

    /**
     * @Route("/urls", name="urls", methods={"GET"})
    */
    public function sendUrlList(UrlRepository $urlRepository)
    {
        $urls = $urlRepository->transformAll();

        return new JsonResponse($urls);

    }

    /**
     * @Route("/add-url", name="add-url", methods={"POST"})
    */
    public function makeShortUrl(Request $request, UrlRepository $urlRepository, EntityManagerInterface $em)
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
        $url->setShortUrl('blank.com');

        if ($request['lifetime'] == '') {

            $url->setLifetime(NULL);

        } else {

            $url->setLifetime(new \DateTime($request['lifetime']));

        }

        $url->setIsActive($request['is_active']);

        $em->persist($url);
        $em->flush();
        
        $this->setStatusCode(201);

        return new JsonResponse($urlRepository->transform($url), $this->statusCode);

    }

}
