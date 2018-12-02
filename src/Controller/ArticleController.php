<?php

namespace App\Controller;

use App\Entity\Article;
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

    /**
     * Finds and displays an article entity.
     *
     * @Route("/{id}", name="article_show", methods={"GET"})
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Article $article)
    {
        //$deleteForm = $this->createDeleteForm($ticket);
//        $message = new Message();
//        $messageForm = $this->createForm(MessageType::class, $message, array(
//            'action' => $this->generateUrl('message_new', ['id' => $ticket->getId()])
//        ));
        //$article->getComments();
//        $arrayDeleteMessageForm = [];
//        foreach ($ticketMessages as $ticketMessage) {
//            $deleteMessageForm = $this->createMessageDeleteForm($ticketMessage);
//            $arrayDeleteMessageForm[$ticketMessage->getId()] = $deleteMessageForm;
//        }
        return $this->render('article/single.html.twig', array(
            'article' => $article,
//            'delete_form' => $deleteForm->createView(),
//            'new_message_form' => $messageForm->createView(),
//            'delete_message_forms' => $arrayDeleteMessageForm
        ));
    }
}
