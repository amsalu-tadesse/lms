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
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute("courses_list");
        }
      
        if($this->isGranted('ROLE_STUDENT'))
        {
            return $this->redirectToRoute("student_course_index");
        }
        else
        {
            
        return $this->render('home/admin_index.html.twig', [
            // 'instructor_courses' => $mylist,
            
        ]);
        }
      
       
    }

    /**
     * @Route("/admin/home", name="admin_home")
     */
   /* public function admin_index()
    {
      
        return $this->render('home/admin_index.html.twig', [
            // 'instructor_courses' => $mylist,
            
        ]);

    }*/
}
