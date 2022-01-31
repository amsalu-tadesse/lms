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
use App\Services\LogService;

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
        $id = preg_replace( '/[^0-9]/', '', $instructor->getId());
        $sql = "update question_answer join instructor_course ic on ic.id=question_answer.course_id set notification = 1 where ic.instructor_id = $id";

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        
        $stmt->execute();

        // $question_answer = $que_ans_repo->updateNotification(['instructor'=>$instructor->getId()]);

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
     * @Route("/live", name="golive", methods={"GET"})
     */
    public function goLive(Request $request): Response
    {
        $link = $request->query->get("link");
        $arr = explode('/',$link);
        if(sizeof($arr) != 4)
        {
            $this->addFlash("danger", "Please enter a proper link. This doesn't work. ");
            return $this->redirectToRoute('question_answer_index');
        }
       
       $arg = $arr[3];
      
        return $this->render('question_answer/golive.html.twig', [
            'arg' =>$arg,
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
    public function new(Request $request, InstructorRepository $inst_repo, LogService $log): Response
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

            $origional = $log->changeObjectToArray($questionAnswer);
            $message = $log->snew($origional, "", "create", $this->getUser(), "questionAnswer");

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
    public function edit(Request $request, QuestionAnswer $questionAnswer, SluggerInterface $slugger, LogService $log, InstructorRepository $inst_repo): Response
    {
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $questionAnswer->getCourse()->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 
        $form = $this->createForm(QuestionAnswerType::class, $questionAnswer);
        $origional = $log->changeObjectToArray($questionAnswer);
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
            if($questionAnswer->getAnsweredAt())
                $questionAnswer->setUpdatedAt(new DateTime);
            else
                $questionAnswer->setAnsweredAt(new DateTime);
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($questionAnswer);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "questionAnswer");
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
    public function delete(Request $request, QuestionAnswer $questionAnswer, LogService $log): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionAnswer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $origional = $log->changeObjectToArray($questionAnswer);
            $entityManager->remove($questionAnswer);
            $entityManager->flush();
            $message = $log->snew($origional, "", "delete", $this->getUser(), "questionAnswer");
        }

        return $this->redirectToRoute('question_answer_index', [], Response::HTTP_SEE_OTHER);
    }
}
