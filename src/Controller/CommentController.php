<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * Creates a new message entity.
     *
     * @Route("/new/{id}", name="comment_new", methods={"POST"})
     */
    public function newAction(Request $request, Article $article)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isValid()){
            $comment->setAuthor($this->getUser());
            $comment->setArticle($article);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('article_show', array('id' => $article->getId()));
    }

    /**
     * Deletes a ticket entity.
     *
     * @Route("/delete/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Comment $comment)
    {
        if ($comment->isDeletionAllowedBy($this->getUser())){
            $form = $this->createCommentDeleteForm($comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($comment);
                $em->flush();
            }
            return $this->redirectToRoute('article_show', array('id' => $comment->getArticle()->getId()));
        }else{
            throw new AccessDeniedException("You can't suppress that commment. If you are admin or the author of the comment, this is a bug.");
        }
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
