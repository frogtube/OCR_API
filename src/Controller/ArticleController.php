<?php
/**
 * Created by PhpStorm.
 * User: uprad
 * Date: 15/05/2018
 * Time: 12:28
 */

namespace App\Controller;


use App\Entity\Article;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{

    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @return Response
     *
     * @Route("/articles/{id}", name="article_show")
     */
    public function showAction()
    {
        $article = new Article();
        $article
            ->setTitle('Mon 1e article')
            ->setContent('Le contenu de mon premier article')
        ;

        $jsonContent = $this->serializer->serialize($article, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/articles", name="article_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();

        $article = $this->serializer->deserialize($data, 'App\Entity\Article', 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();


        return new Response('Well done', Response::HTTP_CREATED);
    }

}