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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/question/answer")
 */
class QuestionAnswerController extends AbstractController
{
    /**
     * @Route("/", name="question_answer_index", methods={"GET"})
     */
    public function index(QuestionAnswerRepository $questionAnswerRepository, QuestionAnswerRepository $que_ans_repo, Request $request, InstructorRepository $inst_repo, PaginatorInterface $paginator): Response
    {
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor) {
            $questions = $questionAnswerRepository->getQuestions($instructor->getId());
        } else {
            $questions = array();
        }
        if (!$instructor) {
            $this->addFlash('warning', 'Only Instructor can use Q&A');
            return $this->redirectToRoute('home');
        }
        $question_answer = $que_ans_repo->updateNotification(['instructor'=>$instructor->getId()]);

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
     * @Route("/load", name="question_answer_load_more", methods={"GET"})
     */
    public function loadMore(QuestionAnswerRepository $questionAnswerRepository, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = array();
            $questions = $questionAnswerRepository->getQuestionAnswer($request->query->get('course'), $request->query->get('start'), $request->query->get('end'));
            foreach ($questions as $key => $value) {
                $result[$key] = $value[0];
                $result[$key]['date'] = $result[$key]['createdAt']->format('d-m-Y');
                $result[$key]['instructor'] = $value['instructor'];
                $result[$key]['student'] = $value['student'];
            }
            $returnResponse = new JsonResponse();
            $returnResponse->setJson(json_encode($result));

            return $returnResponse;
        } else {
            die("error");
        }
    }

    /**
     * @Route("/new", name="question_answer_new", methods={"GET","POST"})
     */
    public function new(Request $request, InstructorRepository $inst_repo): Response
    {
        $questionAnswer = new QuestionAnswer();
        $instObj =  $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        $inid =  $instObj->getId();

        $form = $this->createForm(QuestionAnswerNewType::class, $questionAnswer, array('inid' => $inid));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_INSTRUCTOR')) {
                $inst = $inst_repo->findOneBy(array('user'=>$this->getUser()->getId()));
                $questionAnswer->setInstructor($inst);
            } elseif ($this->isGranted('ROLE_STUDENT')) {
                $questionAnswer->setStudent($this->getUser()->getProfile());
            }
            $questionAnswer->setCreatedAt(new DateTime());
            $questionAnswer->setIsReply(0);
            $questionAnswer->setNotification(0);
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
     * @Route("/{id}/edit", name="question_answer_edit", methods={"GET","POST", "PATCH"})
     */
    public function edit(Request $request, QuestionAnswer $questionAnswer, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(QuestionAnswerType::class, $questionAnswer);
        $form->handleRequest($request, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $videoAnswer = $form['videoAnswer']->getData();
            if ($videoAnswer) {
                $originalFilename = pathinfo($videoAnswer->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$videoAnswer->guessExtension();
                try {
                    $videoAnswer->move(
                        $this->getParameter('uploading_directory_videos'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                    // ... handle exception if something happens during file upload
                }

                // updates the 'videoAnswername' property to store the PDF file name
                // instead of its contents
                $questionAnswer->setVideoAnswer($newFilename);
            }
            else{
                // $questionAnswer->setVideoAnswer($questionAnswer->getVideoAnswer());
            }
            $inst = $this->getUser()->getInstructors()->getValues();

            $questionAnswer->setInstructor($inst[0]);
            $questionAnswer->setAnsweredAt(new DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question_answer/edit.html.twig', [
            'question_answer' => $questionAnswer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/notification/new", name="new_question_notification", methods={"GET"})
     */
    public function notification(QuestionAnswerRepository $questionAnswerRepository, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = array();
            $questions = $questionAnswerRepository->newQuestionNotification($request->query->get('id'));

            $returnResponse = new JsonResponse();
            $returnResponse->setJson($questions['new_questions']);

            return $returnResponse;
        } else {
            die("error");
        }
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
