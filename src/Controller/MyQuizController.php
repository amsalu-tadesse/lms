<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Form\ExamType;
use App\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\InstructorCourse;
use App\Entity\Instructor;
use App\Entity\InstructorCourseChapter;

/**
 * @Route("/exam")
 */
class MyQuizController extends AbstractController
{
     /**
     * @Route("/quizs/{id}", name="quiz", methods={"GET"})
     */
    public function quiz(InstructorCourse $instructorCourse, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $instructorCourseChapters = $em->getRepository(InstructorCourseChapter::class)->findBy(['instructorCourse'=>$instructorCourse]);
        return $this->render('myquiz/index.html.twig', [
            'instructor_course_chapters' =>  $instructorCourseChapters,
            'instructor_course' =>  $instructorCourse,
        //  'incrsid' => $request->get('id'),
        ]);
    }
}
