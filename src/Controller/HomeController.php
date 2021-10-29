<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\InstructorCourseRepository;




class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(InstructorCourseRepository $course)
    {
        if(!$this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $courses = $course->findCoursesSortByCategory();
            return $this->render('course/couses_list.html.twig',[
                'courses' => $courses
            ]);
            //return $this->redirectToRoute("courses_list");
        }
      
        if($this->isGranted('ROLE_STUDENT'))
        {
            return $this->redirectToRoute("student_course_index");
        }
        else
        {
            
        return $this->render('home/admin_index.html.twig', [
            
        ]);
        }
      
       
    }


 
}
