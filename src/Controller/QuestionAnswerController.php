<?php

namespace App\Controller;

use App\Entity\QuestionAnswer;
use App\Form\QuestionAnswerType;
use App\Form\QuestionAnswerNewType;
use App\Repository\InstructorRepository;
use App\Repository\QuestionAnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/question/answer")
 */
class QuestionAnswerController extends AbstractController
{
    /**
     * @Route("/", name="question_answer_index", methods={"GET"})
     */
    public function index(QuestionAnswerRepository $questionAnswerRepository, Request $request, InstructorRepository $inst_repo, PaginatorInterface $paginator): Response
    {
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        $questions = $questionAnswerRepository->getQuestions($instructor->getId());

        $data = $paginator->paginate(
            $questions,
            $request->query->getInt('page', 1),
            15
        );
        return $this->render('question_answer/index.html.twig', [
            'question_answers' => $data,
        ]);
    }

    /**
     * @Route("/new", name="question_answer_new", methods={"GET","POST"})
     */
    public function new(Request $request, InstructorRepository $inst_repo): Response
    {
        $questionAnswer = new QuestionAnswer();
        $form = $this->createForm(QuestionAnswerNewType::class, $questionAnswer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($this->isGranted('ROLE_INSTRUCTOR'))
            {
                $inst = $inst_repo->findOneBy(array('user'=>$this->getUser()->getId()));
                $questionAnswer->setInstructor($inst);
            
            }
            elseif($this->isGranted('ROLE_STUDENT'))
                $questionAnswer->setStudent($this->getUser()->getProfile());
            $questionAnswer->setCreatedAt(new DateTime());
            $questionAnswer->setIsReply(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($questionAnswer);
            $entityManager->flush();

            return $this->redirectToRoute('question_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question_answer/new.html.twig', [
            'question_answer' => $questionAnswer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="question_answer_show", methods={"GET"})
     */
    public function show(QuestionAnswer $questionAnswer): Response
    {
        return $this->render('question_answer/show.html.twig', [
            'question_answer' => $questionAnswer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_answer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuestionAnswer $questionAnswer): Response
    {
        $form = $this->createForm(QuestionAnswerType::class, $questionAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inst = $this->getUser()->getInstructors()->getValues();
            
            $questionAnswer->setInstructor($inst[0]);
            $questionAnswer->setAnsweredAt(new DateTime);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question_answer/edit.html.twig', [
            'question_answer' => $questionAnswer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="question_answer_delete", methods={"POST"})
     */
    public function delete(Request $request, QuestionAnswer $questionAnswer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionAnswer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($questionAnswer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_answer_index', [], Response::HTTP_SEE_OTHER);
    }
}
