<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{JsonResponse, Request};
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Entity\Url;

class UrlShortenerController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
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

    /**
     * @Route("/add-url", name="add-url")
     * @Method("POST")
    */
    public function makeShortUrl()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $url = new Url();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());

    }
}
