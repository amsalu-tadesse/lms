<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizQuestions;
use App\Entity\QuizChoices;
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
            'quiz_questions' => $quizQuestionsRepository->findBy(['quiz' => $quiz]),
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/new/{id}", name="quiz_questions_new", methods={"GET","POST"})
     */
    function new (Request $request, Quiz $quiz): Response {

        $quizQuestion = new QuizQuestions();
        $form = $this->createForm(QuizQuestionsType::class, $quizQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postedData = $request->request->all();
          
            unset($postedData['quiz_questions']);

           /* if (!array_key_exists(strtoupper($form->getData()->getAnswer()), $postedData)) {
                end($postedData); // move the internal pointer to the end of the array
                $key = key($postedData);
                $this->addFlash('danger', 'Please enter the correct answer ranged from A to ' . $key);
                return $this->redirectToRoute('quiz_questions_new', ['id' => $quiz->getId()]);
            }*/

            $entityManager = $this->getDoctrine()->getManager();
            $quizQuestion->setQuiz($quiz);
            $entityManager->persist($quizQuestion);
            $entityManager->flush();

            $answer = null;
 
            foreach ($postedData as $letter => $description) {
                if($letter=="answer")
                {
                    $answer = $description;
                    $quizQuestion->setAnswer($answer);
                    continue;
                }

                $choice = new QuizChoices();
                $choice->setLetter($letter);
                $choice->setDescription($description);
                $choice->setQuestion($quizQuestion);
                $entityManager->persist($choice);
                 
               
            } 
           
         
            $entityManager->flush();

            return $this->redirectToRoute('quiz_questions_index', ['id' => $quiz->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz_questions/new.html.twig', [
            'quiz_question' => $quizQuestion,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="quiz_questions_show", methods={"GET"})
     */
    public function show(QuizQuestions $quizQuestion): Response
    {
        return $this->render('quiz_questions/show.html.twig', [
            'quiz_question' => $quizQuestion,
        ]);
    }

    /*public function numberToArrays($num)
    {
        if ($num > 10 || $num <= 0) {
            return [];
        }

        $arr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        return array_slice($arr, 0, $num);
    }
*/
    /**
     * @Route("/{id}/edit", name="quiz_questions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizQuestions $quizQuestion): Response
    {
        
        $form = $this->createForm(QuizQuestionsType::class, $quizQuestion);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $postedData = $request->request->all();
            unset($postedData['quiz_questions']);
            // dd($postedData );
            $entityManager = $this->getDoctrine()->getManager();

            $answer = null;
 
            foreach ($postedData as $letter => $description) {
                if($letter=="answer")
                {
                    $answer = $description;
                    $quizQuestion->setAnswer($answer);
                    continue;
                }

                $choice = new QuizChoices();
                $choice->setLetter($letter);
                $choice->setDescription($description);
                $choice->setQuestion($quizQuestion);
                $entityManager->persist($choice);
               
            } 
           
         
            $entityManager->flush();

            return $this->redirectToRoute('quiz_questions_index', ['id'=>$quizQuestion->getId()], Response::HTTP_SEE_OTHER);
        }
        $choicelist = array();
        foreach ($quizQuestion->getQuizChoices() as $choice) {
            $choicelist[] = array('letter'=>$choice->getLetter(),'description'=>$choice->getDescription());
        }

        // dd( json_encode($choicelist));

        return $this->renderForm('quiz_questions/edit.html.twig', [
            'quiz_question' => $quizQuestion,
            'choicelist' => json_encode($choicelist),
            'form' => $form,
            
        ]);
    }

    /**
     * @Route("/delete/{id}", name="quiz_questions_delete", methods={"POST"})
     */
    public function delete(Request $request, QuizQuestions $quizQuestion): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quizQuestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quizQuestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_questions_index', [], Response::HTTP_SEE_OTHER);
    }
}
