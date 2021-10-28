<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quizzes")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/list/{id}", name="quiz_index", methods={"GET"})
     */
    public function index(QuizRepository $quizRepository, InstructorCourse $instructorCourse): Response
    {
        $chapters = $instructorCourse->getInstructorCourseChapters();
        $quizzes = array();
        foreach ($chapters as $chapter) {
            if ($chapter->getQuizzes()[0]) {
                $quizzes[] = $chapter->getQuizzes()[0];
            }

        } 
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
            'instructorCourse' => $instructorCourse,
        ]);
    }

    /**
     * @Route("/new/{id}", name="quiz_new", methods={"GET","POST"})
     */
    function new (Request $request, InstructorCourse $instructorCourse): Response {

        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $cp = $form->getData()->getInstructorCourseChapter();
            $chpFromDb = $entityManager->getRepository(Quiz::class)->findBy(['instructorCourseChapter' => $cp]);
            if ($chpFromDb) {
                $this->addFlash("warning", "Quiz has been created for this chapter.");
                $this->redirectToRoute("exam_index");
                return $this->redirectToRoute('quiz_index', ['id' => $instructorCourse->getId()], Response::HTTP_SEE_OTHER);

            }

            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_index', ['id' => $instructorCourse->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_show", methods={"GET"})
     */
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quiz $quiz): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('quiz_index', ['id' => $quiz->getInstructorCourseChapter()->getInstructorCourse()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="quiz_delete", methods={"POST"})
     */
    public function delete(Request $request, Quiz $quiz): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}
