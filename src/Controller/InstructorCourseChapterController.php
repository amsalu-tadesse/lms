<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\Instructor;
use App\Entity\InstructorCourseChapter;
use App\Form\InstructorCourseChapterType;
use App\Repository\InstructorCourseChapterRepository;
use App\Repository\InstructorCourseRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mycourses")
 */
class InstructorCourseChapterController extends AbstractController
{
   
    /**
     * @Route("/", name="instructor_course_chapter_index", methods={"GET"})
     */
    public function index(InstructorCourseRepository $instructorCourseRepository): Response
    {

        $em = $this->getDoctrine()->getManager();

        // $teachersList = $em->getRepository(Instructor::class)->findAll();
        //    dd($instructorCourseRepository->findAll());
        $mylist = $em->getRepository(InstructorCourse::class)->findByUser($this->getUser());
     
        return $this->render('instructor_course_chapter/instructor_vew.html.twig', [
            'instructor_courses' => $mylist,
            // 'instructorsList' => $teachersList,
        ]);

    }

     /**
     * @Route("/chapters/{id}", name="instructor_course_chapter_content_list", methods={"GET"})
     */
    public function contentList(InstructorCourse $instructorCourse, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $instructorCourseChapters = $em->getRepository(InstructorCourseChapter::class)->findBy(['instructorCourse'=>$instructorCourse]);
        return $this->render('instructor_course_chapter/index.html.twig', [
            'instructor_course_chapters' =>  $instructorCourseChapters,
            'instructor_course' =>  $instructorCourse,
        //  'incrsid' => $request->get('id'),
        ]);
    }

     /**
     * @Route("/quiz/{id}", name="quiz", methods={"GET"})
     */
    public function quiz(InstructorCourse $instructorCourse, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $instructorCourseChapters = $em->getRepository(InstructorCourseChapter::class)->findBy(['instructorCourse'=>$instructorCourse]);
        return $this->render('quiz/index.html.twig', [
            'instructor_course_chapters' =>  $instructorCourseChapters,
            'instructor_course' =>  $instructorCourse,
        //  'incrsid' => $request->get('id'),
        ]);
    }

    /**
     * @Route("/new/{id}", name="instructor_course_chapter_new", methods={"GET","POST"})
     */
    public function new(Request $request, InstructorCourse $instructorCourse): Response
    {;
        $incrsid = $request->get('id');
        $instructorCourseChapter = new InstructorCourseChapter();
        $form = $this->createForm(InstructorCourseChapterType::class, $instructorCourseChapter);
        $form->handleRequest($request);
        $instructorCourseChapter->setCreatedAt(new DateTime());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $instructorCourseChapter->setInstructorCourse($instructorCourse);
            $entityManager->persist($instructorCourseChapter);
            $entityManager->flush();

           return $this->redirectToRoute('instructor_course_chapter_content_list', ['id'=>$incrsid], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instructor_course_chapter/new.html.twig', [
            'instructor_course_chapter' => $instructorCourseChapter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="instructor_course_chapter_show", methods={"GET"})
     */
    public function show(InstructorCourseChapter $instructorCourseChapter): Response
    {
        return $this->render('instructor_course_chapter/show.html.twig', [
            'instructor_course_chapter' => $instructorCourseChapter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="instructor_course_chapter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InstructorCourseChapter $instructorCourseChapter): Response
    {
        $form = $this->createForm(InstructorCourseChapterType::class, $instructorCourseChapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('instructor_course_chapter_content_list', ['id'=> $request->get("id")]);
        }

        return $this->renderForm('instructor_course_chapter/edit.html.twig', [
            'instructor_course_chapter' => $instructorCourseChapter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="instructor_course_chapter_delete", methods={"POST"})
     */
    public function delete(Request $request, InstructorCourseChapter $instructorCourseChapter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instructorCourseChapter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($instructorCourseChapter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('instructor_course_chapter_index', [], Response::HTTP_SEE_OTHER);
    }
}
