<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $articleList = $em->getRepository('App:Article')->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articleList,
        ]);
    }
}
