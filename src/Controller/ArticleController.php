<?php
/**
 * Created by PhpStorm.
 * User: uprad
 * Date: 15/05/2018
 * Time: 12:28
 */

namespace App\Controller;


use App\Entity\Article;
use JMS\Serializer\SerializationContext;
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
    public function showAction(Article $article)
    {
        $data = $this->serializer->serialize(
            $article,
            'json',
            SerializationContext::create()->setGroups(array('detail'))
        );

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/articles", name="article_list")
     * @Method({"GET"})
     * @return Response
     */
    public function listAction()
    {
        $articles = $this->getDoctrine()
                         ->getRepository(Article::class)
                         ->findAll();

        $data = $this->serializer->serialize(
            $articles,
            'json',
            SerializationContext::create()->setGroups(array('list'))
        );

        $response = new Response($data);

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