<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\StudentCourse;
use App\Form\StudentCourseType;
use App\Repository\StudentCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/stud")
 */
class StudentCourseController extends AbstractController
{
    /**
     * @Route("/", name="student_course_index", methods={"GET"})
     */

    // ******** student home page , don't touch it OK
    public function index(StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder=$studentCourseRepository->findCourses(1);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('student_course/student.html.twig', [
            'student_courses' => $data,
        ]);
    }
    /**
     * @Route("/course/{id}", name="students_in_course", methods={"GET"})
     */

    public function listStudentUnderCourse(StudentCourseRepository $studentCourseRepository, InstructorCourse $instructorCourse, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder=$studentCourseRepository->findBy(['instructorCourse'=>$instructorCourse]);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('student_course/students_in_course.html.twig', [
            'student_courses' => $data,
            'instructor_course' => $instructorCourse,
        ]);
    }
    /**
     * @Route("/course/diactivate/{id}", name="student_course_deactivate", methods={"GET","POST"})
     */

    public function studentCourseDeactivate(StudentCourse $studentCourse, StudentCourseRepository $studentCourseRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($studentCourse->getActive()) {
            $studentCourse->setActive(false);
        } else {
            $studentCourse->setActive(true);
        }
        $em->flush();
        

        $queryBuilder=$studentCourseRepository->findBy(['instructorCourse'=>$studentCourse->getInstructorCourse()]);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('student_course/students_in_course.html.twig', [
            'student_courses' => $data,
            'instructor_course' => $studentCourse->getInstructorCourse(),
        ]);
        
    }

    /**
     * @Route("/new", name="student_course_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $studentCourse = new StudentCourse();
        $form = $this->createForm(StudentCourseType::class, $studentCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studentCourse);
            $entityManager->flush();

            return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_course/new.html.twig', [
            'student_course' => $studentCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_show", methods={"GET"})
     */
    public function show(StudentCourse $studentCourse): Response
    {
        return $this->render('student_course/show.html.twig', [
            'student_course' => $studentCourse,
        ]);
    }

    /**
     * @Route("/home", methods={"GET"})
     */
    public function student()
    {
        return $this->render('student_course/student.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="student_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StudentCourse $studentCourse): Response
    {
        //dont use this section, it is not usefull.
        $form = $this->createForm(StudentCourseType::class, $studentCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_course/edit.html.twig', [
            'student_course' => $studentCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_delete", methods={"POST"})
     */
    public function delete(Request $request, StudentCourse $studentCourse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studentCourse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($studentCourse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
