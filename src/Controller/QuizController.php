<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseChapter;
use App\Entity\Quiz;
use App\Entity\StudentQuestion;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Repository\QuizQuestionsRepository;
use App\Repository\StudentAnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use DateTime;

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
        // $form = $this->createForm(QuizType::class, $quiz);
    
           // $quizLists =  array();
           $chapters = $instructorCourse->getInstructorCourseChapters();
           $registeredChaptersid = array();
           foreach ($chapters as $chapter) {
               $temp = $chapter->getQuizzes();
               foreach ($temp as $qz) {
                   // $quizLists[]= $qz;
                   $registeredChaptersid[] = $qz->getInstructorCourseChapter()->getId();
               }
           }

      
        $form = $this->createForm(QuizType::class, $quiz, array('registeredChaptersid' => $registeredChaptersid));
        
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
     * @Route("/{id}/quiz", name="course_quiz")
     */
    public function quizPage(Request $request, InstructorCourseChapter $chapter, QuizQuestionsRepository $quiz_que_rep, PaginatorInterface $paginator)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository(Quiz::class)->findOneBy(array('instructorCourseChapter'=> $chapter->getId()));
        $prev = $em->getRepository(StudentQuestion::class)->find1($quiz->getId(), $this->getUser()->getProfile()->getId());
        $question = "";
        $i = 1;

        $quiz_que = $quiz_que_rep->getQ($quiz->getId());
        
        if($prev == null){
            if($quiz->getActiveQuestions() < sizeof($quiz_que)){
                $question_ids = array();
                foreach($quiz_que as $key => $value)
                {
                    $question_ids[] = $value['id'];
                }

                $sizof_question_ids = sizeof($question_ids);
                $selected = array();
                $control = $quiz->getActiveQuestions();

                while($control != 0)
                {
                    $random_id = rand(0, $control);
                    $selected[] = $question_ids[$random_id];
                    array_splice($question_ids, $random_id,1);
                    $control--;
                }

                foreach($quiz_que as $quiz_q){
                    $studentQuestion = new StudentQuestion();
                    if(in_array($quiz_q['id'], $selected))
                    {
                        $studentQuestion->setStudent($this->getUser()->getProfile());
                        $studentQuestion->setQuestion($quiz_que_rep->find($quiz_q['id']));
                        $studentQuestion->setAnsweredAt(new DateTime());
                        $em->persist($studentQuestion);
                        $em->flush();  
                    }
                    else{
                        
                    }
                }

            }
            else{
                foreach($quiz_que as $quiz_q){
                    $studentQuestion = new StudentQuestion();
                    if($i == 1)
                    {
                        $question = $quiz_q;
                    }
                    $studentQuestion->setStudent($this->getUser()->getProfile());
                    $studentQuestion->setQuestion($quiz_que_rep->find($quiz_q['id']));
                    $studentQuestion->setAnsweredAt(new DateTime());
                    $em->persist($studentQuestion);
                    $em->flush();
                }
            }
        }
        
        // $quiz = $quiz_rep->findBy(array('instructorCourseChapter'=>$chapter->getId()));
        
        $quiz_size = sizeof($quiz_que);

        if($quiz_size > 0)
        {
            $data = $paginator->paginate(
                $quiz_que,
                $request->query->getInt('page',1),
                1
            );

            return $this->render('instructor_course_chapter/quiz.html.twig', [
                    'quiz' => $quiz,
                    'chapter' => $chapter,
                    'quiz_ques' => $data
                ]);
        }

        return $this->redirectToRoute('course_list',['course'=>$chapter->getInstructorCourse()->getId(), 'chapter'=> $chapter->getTopic()]);

        
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
    public function edit(Request $request, Quiz $quiz, InstructorCourse $instructorCourse): Response
    {    
        $chapters = $instructorCourse->getInstructorCourseChapters();
        $registeredChaptersid = array();
        foreach ($chapters as $chapter) {
            $temp = $chapter->getQuizzes();
            foreach ($temp as $qz) {
                $registeredChaptersid[] = $qz->getInstructorCourseChapter()->getId();
            }
        }

        $form = $this->createForm(QuizType::class,$quiz, array('registeredChaptersid' => $registeredChaptersid));
        
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
