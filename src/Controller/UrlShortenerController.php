<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Entity\Url;

class UrlShortenerController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('url_shortener/index.html.twig');
    }

     /**
     * @Route("/urls", name="urls")
     * @Method("GET")
     */
    public function getUrlList()
    {
        $urls = $this->getDoctrine()
            ->getRepository(Url::class)
            ->findAll();

        foreach($urls as $url) {

            $arrayCollection[] = array(

                'id' => $url->getId(),
                'long_url' => $url->getLongUrl(),
                'short_url' => $url->getShortUrl(),
                'lifetime' => $url->getLifetime(),
                'is_active' => $url->getIsActive()

            );
        }

        return new JsonResponse($arrayCollection);

    }
}
