<?php

namespace App\Controller;

use App\Entity\QuizQuestions;
use App\Entity\Quiz;
use App\Form\QuizQuestionsType;
use App\Repository\QuizQuestionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz/questions")
 */
class QuizQuestionsController extends AbstractController
{
    /**
     * @Route("/{id}", name="quiz_questions_index", methods={"GET"})
     */
    public function index(QuizQuestionsRepository $quizQuestionsRepository, Quiz $quiz): Response
    {
        return $this->render('quiz_questions/index.html.twig', [
            'quiz_questions' => $quizQuestionsRepository->findBy(['quiz'=>$quiz]),
            'quiz'=>$quiz,
        ]);
    } 

    /**
     * @Route("/new/{id}", name="quiz_questions_new", methods={"GET","POST"})
     */
    public function new(Request $request, Quiz $quiz): Response
    {
        $quizQuestion = new QuizQuestions();
        $form = $this->createForm(QuizQuestionsType::class, $quizQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $quizQuestion->setQuiz($quiz);
            $entityManager->persist($quizQuestion);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_questions_index', ['id'=>$quiz->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_questions/new.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_questions_show", methods={"GET"})
     */
    public function show(QuizQuestions $quizQuestion): Response
    {
        return $this->render('quiz_questions/show.html.twig', [
            'quiz_question' => $quizQuestion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_questions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizQuestions $quizQuestion): Response
    {
        $form = $this->createForm(QuizQuestionsType::class, $quizQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quiz_questions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_questions/edit.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_questions_delete", methods={"POST"})
     */
    public function delete(Request $request, QuizQuestions $quizQuestion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizQuestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quizQuestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_questions_index', [], Response::HTTP_SEE_OTHER);
    }
}
