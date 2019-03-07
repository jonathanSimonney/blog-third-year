<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_list")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $projectList = $em->getRepository('App:Project')->findAll();
        return $this->render('project/index.html.twig', [
            'projects' => $projectList,
        ]);
    }
}
