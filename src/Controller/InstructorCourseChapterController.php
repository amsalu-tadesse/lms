<?php

namespace App\Controller;

use App\Entity\InstructorCourseChapter;
use App\Form\InstructorCourseChapterType;
use App\Repository\InstructorCourseChapterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/instructor/course/chapter")
 */
class InstructorCourseChapterController extends AbstractController
{
    /**
     * @Route("/", name="instructor_course_chapter_index", methods={"GET"})
     */
    public function index(InstructorCourseChapterRepository $instructorCourseChapterRepository): Response
    {
        return $this->render('instructor_course_chapter/index.html.twig', [
            'instructor_course_chapters' => $instructorCourseChapterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="instructor_course_chapter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $instructorCourseChapter = new InstructorCourseChapter();
        $form = $this->createForm(InstructorCourseChapterType::class, $instructorCourseChapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($instructorCourseChapter);
            $entityManager->flush();

            return $this->redirectToRoute('instructor_course_chapter_index', [], Response::HTTP_SEE_OTHER);
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

            return $this->redirectToRoute('instructor_course_chapter_index', [], Response::HTTP_SEE_OTHER);
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
