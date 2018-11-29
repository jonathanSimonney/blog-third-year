<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $author = $this->getNewUser("author@gmail.com", "password", "ROLE_BLOGGER");
        $commenter = $this->getNewUser("commenter@gmail.com", "password", "ROLE_USER");
        $stalker = $this->getNewUser("stalker@gmail.com", "password", "ROLE_USER");


        // $product = new Product();
        $commentedArticle = $this->getNewArticle("un article avec commentaires", "<p>Beaucoup de lorem ipsum, avec </p> <p>des paragraphes qui ne veulent rien dire</p>", $author, new \DateTime('now'));

        $this->addCommentToArticle("commentaire nul", $commenter, $commentedArticle);

        $uncommentedArticle = $this->getNewArticle("un article sans commentaires", "<p>Beaucoup moins de lorem ipsum, avec </p> <p>des paragraphes qui ne veulent toujours rien dire</p>", $author, new \DateTime('now'));


        $manager->persist($author);
        $manager->persist($commenter);
        $manager->persist($stalker);
        $manager->persist($commentedArticle);
        $manager->persist($uncommentedArticle);

        $manager->flush();
    }

    private function addCommentToArticle($content, $commentAuthor, Article $article){
        $comment = new Comment();
        $comment->setAuthor($commentAuthor);
        $comment->setContent($content);
        $article->addComment($comment);
    }

    private function getNewArticle($title, $content, $author, $creationDate){
        $article = new Article();

        $article->setTitle($title);
        $article->setContent($content);
        $article->setAuthor($author);
        $article->setCreatedAt($creationDate);

        return $article;
    }

    private function getNewUser($email, $password, $role){
        $author = new User();

        $author->setEmail($email);
        $author->setRoles([$role]);

        $author->setPassword($this->passwordEncoder->encodePassword(
            $author, $password
        ));

        return $author;
    }
}
