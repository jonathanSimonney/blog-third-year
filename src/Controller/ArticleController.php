<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
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
     * create a new article entity.
     *
     * @Route("/new", name="new_article", methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $article = new Article();
        $article->setCreatedAt(new \DateTime());
        $article->setAuthor($this->getUser());
        $options = array('isAdmin' => $this->isGranted('ROLE_ADMIN'));
        $form = $this->createForm('App\Form\ArticleType', $article, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_show', array('id' => $article->getId()));
        }
        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * create a new article entity.
     *
     * @Route("/edit/{id}", name="edit_article", methods={"GET", "PATCH"})
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editAction(Request $request, Article $article)
    {
        if ($article->isEditableByUser($this->getUser())){
            $options = array(
                'isAdmin' => $this->isGranted('ROLE_ADMIN'),
                'isEdit'  => true,
            );
            $form = $this->createForm('App\Form\ArticleType', $article, $options);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                return $this->redirectToRoute('article_show', array('id' => $article->getId()));
            }
            return $this->render('article/new.html.twig', array(
                'article' => $article,
                'form' => $form->createView(),
                'isEdit' => true,
            ));
        }else{
            throw new AccessDeniedException("You can't edit that article. If you are admin or the author of the article, this is a bug.");
        }
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
        $arrayDeleteCommentForm = [];
        $commentForm = false;
        $showEditLink = false;

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

            $showEditLink = $article->isEditableByUser($this->getUser());
        }
        return $this->render('article/single.html.twig', array(
            'article' => $article,
            'new_comment_form' => $commentForm,
            'delete_comment_forms' => $arrayDeleteCommentForm,
            'show_edit_link'       => $showEditLink,
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
