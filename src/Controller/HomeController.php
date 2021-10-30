<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\InstructorCourseRepository;
use Ottosmops\Pdftotext\Extract;




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
      /**
     * @Route("/pdf", name="test")
     */
        public function temp()
        {
           /* //one
          $txt =  \Ottosmops\Pdftotext\Extract::getText('uploads/pdf/law.pdf'); //returns the text from the pdf

          //three
         $txt = (new Extract('/usr/bin/pdftotext'))
    ->pdf('uploads/pdf/law.pdf')
    ->options('-enc UTF-8 -nopgbrk')
    ->text();
  

  //two////
  $reader = new \Asika\Pdf2text;
$output = $reader->decode('uploads/pdf/law.pdf');

          return $this->render('home/pdf.html.twig', [
            'pdf' => $txt,
        ]);*/

        }

 
}
