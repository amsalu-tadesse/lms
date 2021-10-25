<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
      
        return $this->redirectToRoute("courses_list");
    }

    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function admin_index()
    {
      
        return $this->render('home/admin_index.html.twig', [
            // 'instructor_courses' => $mylist,
            
        ]);

    }
}
