<?php

namespace App\Controller;

use App\Entity\QuizChoices;
use App\Form\QuizChoicesType;
use App\Repository\QuizChoicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\LogService;

/**
 * @Route("/quiz/choices")
 */
class QuizChoicesController extends AbstractController
{
    /**
     * @Route("/", name="quiz_choices_index", methods={"GET"})
     */
    public function index(QuizChoicesRepository $quizChoicesRepository): Response
    {
        $this->denyAccessUnlessGranted('quiz_list');
        return $this->render('quiz_choices/index.html.twig', [
            'quiz_choices' => $quizChoicesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="quiz_choices_new", methods={"GET","POST"})
     */
    public function new(Request $request, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('quiz_create');
        $quizChoice = new QuizChoices();
        $form = $this->createForm(QuizChoicesType::class, $quizChoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizChoice);
            $entityManager->flush();
            $origional = $log->changeObjectToArray($quizChoice);
            $message = $log->snew($origional, "", "create", $this->getUser(), "quizChoice");
            return $this->redirectToRoute('quiz_choices_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_choices/new.html.twig', [
            'quiz_choice' => $quizChoice,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_choices_show", methods={"GET"})
     */
    public function show(QuizChoices $quizChoice): Response
    {
        $this->denyAccessUnlessGranted('quiz_list');
        return $this->render('quiz_choices/show.html.twig', [
            'quiz_choice' => $quizChoice,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_choices_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizChoices $quizChoice,LogService $log): Response
    {
        $this->denyAccessUnlessGranted('quiz_edit');
        $origional = $log->changeObjectToArray($quizChoice);
        $form = $this->createForm(QuizChoicesType::class, $quizChoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($quizChoice);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "quizChoice");
            return $this->redirectToRoute('quiz_choices_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_choices/edit.html.twig', [
            'quiz_choice' => $quizChoice,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_choices_delete", methods={"POST"})
     */
    public function delete(Request $request, QuizChoices $quizChoice, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('quiz_delete');
        if ($this->isCsrfTokenValid('delete'.$quizChoice->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $origional = $log->changeObjectToArray($quizChoice);
            $entityManager->remove($quizChoice);
            $entityManager->flush();
            $message = $log->snew($origional, "", "delete", $this->getUser(), "quizChoice");

        }

        return $this->redirectToRoute('quiz_choices_index', [], Response::HTTP_SEE_OTHER);
    }
}
