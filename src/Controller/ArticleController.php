<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
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
        $arrayDeleteCommentForm = [];
        $commentForm = false;

        if ($this->isGranted('ROLE_USER')){
            $comment = new Comment();
            $commentForm = $this->createForm(CommentType::class, $comment, array(
                'action' => $this->generateUrl('comment_new', ['id' => $article->getId()])
            ))->createView();

            $articleComments = $article->getComments();
            $currentUser = $this->getUser();
            foreach ($articleComments as $existingComment) {
                if ($existingComment->isDeletionAllowedBy($currentUser)){
                    $deleteCommentForm = $this->createCommentDeleteForm($existingComment);
                    $arrayDeleteCommentForm[$existingComment->getId()] = $deleteCommentForm;
                }
            }
        }
        return $this->render('article/single.html.twig', array(
            'article' => $article,
//            'delete_form' => $deleteForm->createView(),
            'new_comment_form' => $commentForm,
            'delete_comment_forms' => $arrayDeleteCommentForm
        ));
    }

    /**
     * Creates a form to delete a comment entity.
     *
     * @param Comment $comment The comment entity
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createCommentDeleteForm(Comment $comment)
    {
        return $this->createFormBuilder()
            ->setMethod('DELETE')
            ->setAction($this->generateUrl('comment_delete', array('id' => $comment->getId())))
            ->getForm()
            ;
    }
}
