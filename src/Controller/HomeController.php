<?php

namespace App\Controller;

use App\Repository\InstructorCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(InstructorCourseRepository $course)
    {

       

        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $courses = $course->findCoursesSortByCategory();
            return $this->render('course/couses_list.html.twig', [
                'courses' => $courses,
            ]);
            //return $this->redirectToRoute("courses_list");
        }

        if ($this->isGranted('ROLE_STUDENT')) {
            return $this->redirectToRoute("student_course_index");
        } else {

            return $this->render('home/admin_index.html.twig', [

            ]);
        }

    }

    /**
     * @Route("/print", name="pdf")
     */
    function print() {
        return $this->render("certificate/print.html.twig");
    }
    /**
     * @Route("/pdf", name="test")
     */
    public function temp()
    {
        /* $projectRoot = $this->getParameter('docroot');
        $dotenv = new Dotenv();
        $dd = $dotenv->load($projectRoot.'/.env');
        //  $_ENV['DATABASE_URL'] = "hahahaha";
        $dbUser =  $dd->getenv('DATABASE_URL');

        dd($dbUser);*/

        /* $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/.env');
        $dbUser = getenv('DB_USER');
        dd($dbUser);

        dd("done");*/

// $c = $_ENV['DATABASE_URL'];
        // $v = $this->params->get('DATABASE_URL');
        // $container->setParameter('DATABASE_URL', '88888888888');
        // $container = $this->get('validator.email');
        // $object = $container->get('foo.baz.bar');
        // dd($container);

    }

}
