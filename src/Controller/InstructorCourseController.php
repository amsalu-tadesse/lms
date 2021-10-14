<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseStatus;
use App\Entity\User;
use App\Form\InstructorCourseType;
use App\Repository\InstructorCourseRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** 
 * @Route("/instructor/course")
 */
class InstructorCourseController extends AbstractController
{
    /**
     * @Route("/", name="instructor_course_index", methods={"GET"})
     */
    public function index(InstructorCourseRepository $instructorCourseRepository): Response
    {
        $em = $this->getDoctrine()->getManager();

        $teachersList = $em->getRepository(Instructor::class)->findAll();
    //    dd($instructorCourseRepository->findAll());
    
        return $this->render('instructor_course/index.html.twig', [
            'instructor_courses' => $instructorCourseRepository->findAll(),
            'instructorsList' => $teachersList,
        ]);
    }

    /**
     * @Route("/new", name="instructor_course_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $instructorCourse = new InstructorCourse();
        $form = $this->createForm(InstructorCourseType::class, $instructorCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $instructorCourseStatus = $entityManager->getRepository(InstructorCourseStatus::class)->find(2);//assigned but waiting
            $instructorCourse->setStatus($instructorCourseStatus);
            $instructorCourse->setCreatedAt(new DateTime());
            $entityManager->persist($instructorCourse);
            $entityManager->flush();

            return $this->redirectToRoute('instructor_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instructor_course/new.html.twig', [
            'instructor_course' => $instructorCourse,
            'form' => $form,
        ]);
    }


     /**
     * @Route("/assign_instructor/{id}", name="assign_instructor", methods={"GET","POST"})
     */
    public function assignInstructor(Request $request, InstructorCourse $instructorCourse)
    {
        $em = $this->getDoctrine()->getManager();
        $instId = $request->request->get("instructor");
        $instructor = $em->getRepository(Instructor::class)->find($instId);
        $instructorCourse->setInstructor($instructor);
        $instructorCourseStatus = $em->getRepository(InstructorCourseStatus::class)->find(2);
        $instructorCourse->setStatus($instructorCourseStatus);
        $em->flush();

        return $this->redirectToRoute("instructor_course_index");
    }



    /**
     * @Route("/{id}", name="instructor_course_show", methods={"GET"})
     */
    public function show(InstructorCourse $instructorCourse): Response
    {
        return $this->render('instructor_course/show.html.twig', [
            'instructor_course' => $instructorCourse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="instructor_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InstructorCourse $instructorCourse): Response
    {
        $form = $this->createForm(InstructorCourseType::class, $instructorCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('instructor_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instructor_course/edit.html.twig', [
            'instructor_course' => $instructorCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="instructor_course_delete", methods={"POST"})
     */
    public function delete(Request $request, InstructorCourse $instructorCourse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instructorCourse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($instructorCourse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('instructor_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
