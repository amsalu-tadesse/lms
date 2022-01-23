<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseChapter;
use App\Entity\Quiz;
use App\Entity\StudentQuestion;
use App\Entity\StudentQuiz;
use App\Form\QuizType;
use App\Repository\QuizQuestionsRepository;
use App\Repository\QuizRepository;
use App\Repository\SystemSettingRepository;
use App\Repository\StudentCourseRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CodeItNow\BarcodeBundle\Utils\QrCode;
use App\Services\LogService;
use App\Repository\InstructorRepository;

/**
 * @Route("/quizzes")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/list/{id}", name="quiz_index", methods={"GET"})
     */
    public function index(QuizRepository $quizRepository, InstructorCourse $instructorCourse, InstructorRepository $inst_repo): Response
    {
        $this->denyAccessUnlessGranted('quiz_list');

        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $instructorCourse->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 

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
    public function new(Request $request, InstructorCourse $instructorCourse, LogService $log, InstructorRepository $inst_repo): Response
    {
        $this->denyAccessUnlessGranted('quiz_create');
        $quiz = new Quiz();
        // $form = $this->createForm(QuizType::class, $quiz);
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $instructorCourse->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 
        // $quizLists =  array();
        $chapters = $instructorCourse->getInstructorCourseChapters();
        $registeredChaptersid = array();
        $unregisteredChaptersid = array();
        foreach ($chapters as $chapter) {
            $temp = $chapter->getQuizzes();
            foreach ($temp as $qz) {
                // $quizLists[]= $qz;
                // dd($qz);
                $registeredChaptersid[] = $qz->getInstructorCourseChapter()->getId();
            }
            $unregisteredChaptersid[] = $chapter->getId();
        }

        $diff = array_diff($unregisteredChaptersid, $registeredChaptersid);
        if (!$diff) {
            $this->addFlash('warning', 'Please add chapters first!!');
            return $this->redirectToRoute('quiz_index', ['id'=>$instructorCourse->getId()]);
        }


        $form = $this->createForm(QuizType::class, $quiz, array('unregisteredChaptersid' => $diff));

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

            $origional = $log->changeObjectToArray($quiz);
            $message = $log->snew($origional, "", "create", $this->getUser(), "quiz");

            return $this->redirectToRoute('quiz_index', ['id' => $instructorCourse->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
            'id' => $instructorCourse->getId(),
        ]);
    }

    /**
    * @Route("/retake/{id}", name="retake_exam", methods={"GET"})
    */
    public function retake(QuizRepository $quizRepository, QuizQuestionsRepository $quiz_que_rep, InstructorCourseChapter $chapter, StudentCourseRepository $stud_course, LogService $log): Response
    {
        $check_student_course = $stud_course->findBy(array('student' => $this->getUser()->getProfile()->getId(), 'instructorCourse' => $chapter->getInstructorCourse()->getId()));
        if ($check_student_course == null) {
            return $this->redirectToRoute("student_course_index");
        }

        $em = $this->getDoctrine()->getManager();
        $i = 1;
        $quiz = $em->getRepository(Quiz::class)->findOneBy(array('instructorCourseChapter' => $chapter->getId()));
        if ($quiz != null) {
            $quiz_que = $quiz_que_rep->getQ($quiz->getId());
            $size_quiz_que = sizeof($quiz_que);
            if ($size_quiz_que) {
                $prev_quiz = $em->getRepository(StudentQuiz::class)->findBy(array('quiz'=>$quiz->getId(), 'student'=>$this->getUser()->getProfile()->getId()), array('id'=>'DESC'), 1, 0);
                $previous_quiz = $prev_quiz[0];
                
                $origional = $log->changeObjectToArray($previous_quiz);
                //deactivate former quiz
                $previous_quiz->setActive(0);
                $em->persist($previous_quiz);
                $em->flush();

                $modified = $log->changeObjectToArray($previous_quiz);
                $message = $log->snew($origional, $modified, "update", $this->getUser(), "studentQuiz");


                $sql = "update student_question sq ".
                       "join quiz_questions qq on sq.question_id = qq.id ".
                       "set sq.active = 0 ".
                       "where qq.quiz_id = ? and sq.student_id = ?";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute([$quiz->getId(), $this->getUser()->getProfile()->getId()]);
                //deactivate previous questions
                // $em->getRepository(StudentQuestion::class)->deactivateQuestions($quiz->getId(), $this->getUser()->getProfile()->getId());

                $student_quiz = new StudentQuiz();
                $student_quiz->setStudent($this->getUser()->getProfile());
                $student_quiz->setQuiz($quiz);

                //change this based on your timezone
                $date = date('Y-m-d H:i:s', time());
                $dateTime = new DateTime($date);
                // $dateTime->modify('+2 hours');
                // $result = $dateTime->format('Y-m-d H:i:s');

                $duration = $quiz->getDuration() + 120;
                $minutes_add = "$duration Minutes";
                $currentTime = date_default_timezone_get();

                $strtotime = strtotime("$date + $minutes_add ");

                $date = new \DateTime('@' . strtotime("$date + $minutes_add"));
                $student_quiz->setCreatedAt(new DateTime());
                $student_quiz->setEndTime($date);
                $student_quiz->setActive(1);
                $student_quiz->setTrial($previous_quiz->getTrial()+1);

                $em->persist($student_quiz);
                $em->flush();

                $origional = $log->changeObjectToArray($student_quiz);
                $message = $log->snew($origional, "", "create", $this->getUser(), "studentQuiz");

                if ($quiz->getActiveQuestions() < sizeof($quiz_que)) {
                    $question_ids = array();
                    foreach ($quiz_que as $key => $value) {
                        $question_ids[] = $value['id'];
                    }

                    $sizof_question_ids = sizeof($question_ids);
                    $selected = array();
                    $control = $quiz->getActiveQuestions();

                    while ($control != 0) {
                        $random_id = rand(0, $control);
                        $selected[] = $question_ids[$random_id];
                        array_splice($question_ids, $random_id, 1);
                        $control--;
                    }

                    foreach ($quiz_que as $quiz_q) {
                        $studentQuestion = new StudentQuestion();
                        if (in_array($quiz_q['id'], $selected)) {
                            $studentQuestion->setStudent($this->getUser()->getProfile());
                            $studentQuestion->setQuestion($quiz_que_rep->find($quiz_q['id']));
                            $studentQuestion->setAnsweredAt(new DateTime());
                            $studentQuestion->setActive(1);
                            $em->persist($studentQuestion);
                            $em->flush();
                            $origional = $log->changeObjectToArray($studentQuestion);
                            $message = $log->snew($origional, "", "create", $this->getUser(), "studentQuestion");
                        }
                    }
                } else {
                    foreach ($quiz_que as $quiz_q) {
                        $studentQuestion = new StudentQuestion();
                        if ($i == 1) {
                            $question = $quiz_q;
                        }
                        $studentQuestion->setStudent($this->getUser()->getProfile());
                        $studentQuestion->setQuestion($quiz_que_rep->find($quiz_q['id']));
                        $studentQuestion->setAnsweredAt(new DateTime());
                        $studentQuestion->setActive(1);
                        $em->persist($studentQuestion);
                        $em->flush();
                        $origional = $log->changeObjectToArray($studentQuestion);
                        $message = $log->snew($origional, "", "create", $this->getUser(), "studentQuestion");
                    }
                }
            }
        }
        return $this->redirectToRoute("course_quiz", ['id'=>$chapter->getId()]);
    }

    /**
     * @Route("/quiz/{id}", name="course_quiz", requirements={"id":"\d{1,5}"})
     */
    public function quizPage(Request $request, SystemSettingRepository $setting_repo, InstructorCourseChapter $chapter, QuizQuestionsRepository $quiz_que_rep, PaginatorInterface $paginator, StudentCourseRepository $stud_course, LogService $log)
    {
        $this->get('session')->getFlashBag()->clear();
        $check_student_course = $stud_course->findBy(array('student' => $this->getUser()->getProfile()->getId(), 'instructorCourse' => $chapter->getInstructorCourse()->getId()));
        if ($check_student_course == null) {
            return $this->redirectToRoute("student_course_index");
        }

        $em = $this->getDoctrine()->getManager();

        $setting = $setting_repo->getValue("show_un_answered_questions");

        $quiz = $em->getRepository(Quiz::class)->findOneBy(array('instructorCourseChapter' => $chapter->getId()));
        if ($quiz != null) {
            $prev = $em->getRepository(StudentQuestion::class)->find1($quiz->getId(), $this->getUser()->getProfile()->getId());

            $question = "";
            $i = 1;
            $quiz_que = $quiz_que_rep->getQ($quiz->getId());
            if (sizeof($quiz_que) > 0) {
                $student_quiz = $em->getRepository(StudentQuiz::class)->findOneBy(array('student' => $this->getUser()->getProfile()->getId(), 'quiz' => $quiz->getId()), array('id'=>'DESC'));
                if ($student_quiz!=null && $student_quiz->getResult() != null) {
                    if ($student_quiz->getResult() < $quiz->getPassValue() && $student_quiz->getTrial() < $quiz->getNoOfRetakeAllowed()) {
                        return $this->redirectToRoute('retake_exam', ['id'=>$chapter->getId()]);
                    }
                }

                if ($prev == null) {
                    $student_quiz = new StudentQuiz();
                    $student_quiz->setStudent($this->getUser()->getProfile());
                    $student_quiz->setQuiz($quiz);

                    //change this based on your timezone
                    $date = date('Y-m-d H:i:s', time());
                    $dateTime = new DateTime($date);
                    // $dateTime->modify('+2 hours');
                    // $result = $dateTime->format('Y-m-d H:i:s');

                    $duration = $quiz->getDuration() + 120;
                    $minutes_add = "$duration Minutes";
                    $currentTime = date_default_timezone_get();

                    $strtotime = strtotime("$date + $minutes_add ");

                    $date = new \DateTime('@' . strtotime("$date + $minutes_add"));
                    $student_quiz->setCreatedAt(new DateTime());
                    $student_quiz->setEndTime($date);
                    $student_quiz->setActive(1);
                    $student_quiz->setTrial(0);
                    $em->persist($student_quiz);
                    $em->flush();
                    $origional = $log->changeObjectToArray($student_quiz);
                    $message = $log->snew($origional, "", "create", $this->getUser(), "studentQuiz");
                    if ($quiz->getActiveQuestions() < sizeof($quiz_que)) {
                        $question_ids = array();
                        foreach ($quiz_que as $key => $value) {
                            $question_ids[] = $value['id'];
                        }

                        $sizof_question_ids = sizeof($question_ids);
                        $selected = array();
                        $control = $quiz->getActiveQuestions();

                        while ($control != 0) {
                            $random_id = rand(0, $control);
                            $selected[] = $question_ids[$random_id];
                            array_splice($question_ids, $random_id, 1);
                            $control--;
                        }

                        foreach ($quiz_que as $quiz_q) {
                            $studentQuestion = new StudentQuestion();
                            if (in_array($quiz_q['id'], $selected)) {
                                $studentQuestion->setStudent($this->getUser()->getProfile());
                                $studentQuestion->setQuestion($quiz_que_rep->find($quiz_q['id']));
                                $studentQuestion->setAnsweredAt(new DateTime());
                                $studentQuestion->setActive(1);
                                $em->persist($studentQuestion);
                                $em->flush();

                                $origional = $log->changeObjectToArray($studentQuestion);
                                $message = $log->snew($origional, "", "create", $this->getUser(), "studentQuestion");
                            }
                        }
                    } else {
                        foreach ($quiz_que as $quiz_q) {
                            $studentQuestion = new StudentQuestion();
                            if ($i == 1) {
                                $question = $quiz_q;
                            }
                            $studentQuestion->setStudent($this->getUser()->getProfile());
                            $studentQuestion->setQuestion($quiz_que_rep->find($quiz_q['id']));
                            $studentQuestion->setAnsweredAt(new DateTime());
                            $studentQuestion->setActive(1);
                            $em->persist($studentQuestion);
                            $em->flush();
                            
                            $origional = $log->changeObjectToArray($studentQuestion);
                            $message = $log->snew($origional, "", "create", $this->getUser(), "studentQuestion");
                        }
                    }
                }

                $test_taken = false;
                $student_quiz = $em->getRepository(StudentQuiz::class)->findOneBy(array('student' => $this->getUser()->getProfile()->getId(), 'quiz' => $quiz->getId(),'active'=>1));
                $last_chapter =  $em->getRepository(InstructorCourseChapter::class)->findBy(array('instructorCourse'=>$chapter->getInstructorCourse()->getId()), array('id'=>'DESC'), 1, 0);

                if ($student_quiz && $student_quiz->getResult() != null) {
                    $test_taken = true;
                }
                $now = new DateTime(date("Y-m-d H:i:s", time()));
                $end_time = new DateTime($student_quiz->getEndTime()->format("Y-m-d H:i:s"));

                $date_diff = $end_time->diff($now);
                $time = array();
                $last = false;


                if ($now < $end_time && !$test_taken) {

                    /// write answer if time available
                    if ($request->query->get('value') != null && $request->query->get('parameter') != null) {
                        $stud_que = $em->getRepository(StudentQuestion::class)->findOneBy(array('student' => $this->getUser()->getProfile()->getId(), 'id' => $request->query->getInt('parameter')));
                        if ($stud_que != null) {
                            $origional = $log->changeObjectToArray($stud_que);
                            $stud_que->setAnswer($request->query->get('value'));
                            $stud_que->setAnsweredAt(new DateTime());
                            $em->persist($stud_que);
                            $em->flush();
                            $modified = $log->changeObjectToArray($stud_que);
                            $message = $log->snew($origional, $modified, "update", $this->getUser(), "studentQuestion");
                        }
                    }

                    $time['minutes'] = $date_diff->i;
                    $time['seconds'] = $date_diff->s;

                    $prev = $em->getRepository(StudentQuestion::class)->find1($quiz->getId(), $this->getUser()->getProfile()->getId());
                    $quiz_size = sizeof($prev);

                    $correct_answer = 0;
                    if ($request->query->get('page') > $quiz_size) {
                        $stud_que = $em->getRepository(StudentQuestion::class)->find1($quiz->getId(), $this->getUser()->getProfile()->getId());

                        foreach ($stud_que as $key => $value) {
                            if (strcmp($value->getAnswer(), $value->getQuestion()->getAnswer()) == 0) {
                                $correct_answer++;
                            }
                        }


                        $res = ($correct_answer/sizeof($stud_que))*$quiz->getPercentage();

                        if ($student_quiz->getResult() == null) {
                            if ($res >= $quiz->getPassValue()) {
                                if (array_key_exists(0, $last_chapter)) {
                                    if ($last_chapter[0]->getId() == $chapter->getId()) {
                                        $finalize_course = $stud_course->findOneBy(['student'=>$this->getUser()->getProfile()->getId(),'instructorCourse'=>$chapter->getInstructorCourse()->getId()]) ;
                                        $id = $finalize_course->getId();

                                        $origional = $log->changeObjectToArray($finalize_course);

                                        $url = $_SERVER['SERVER_NAME']."/certificate/".$this->getUser()->getProfile()->getStudentId()."/".$id;
                                        $finalize_course->setStatus(5);
                                        $finalize_course->setCompletedAt(new DateTime);
                                        $finalize_course->setQrUrl($url);
                                        $finalize_course->setQrCode($this->qrGenerator($url));
                                        $em->persist($finalize_course);
                                        $em->flush();
                                        
                                        $modified = $log->changeObjectToArray($finalize_course);
                                        $message = $log->snew($origional, $modified, "update", $this->getUser(), "studentCourse");
                                    }
                                }
                            }
                            else{
                                $this->addFlash("info", "You are faile the exam");
                            }

                            $origional = $log->changeObjectToArray($student_quiz);

                            $student_quiz->setResult($res);
                            $em->persist($student_quiz);
                            $em->flush();

                            $modified = $log->changeObjectToArray($student_quiz);
                            $message = $log->snew($origional, $modified, "update", $this->getUser(), "studentQuiz");
                        }

                        if ($res >= $quiz->getPassValue()) {
                            if (array_key_exists(0, $last_chapter)) {
                                if ($last_chapter[0]->getId() == $chapter->getId()) {
                                    $last = true;
                                    $this->addFlash("info", "You are successfully completed this course. you can get your certificate");
                                }
                            }
                        } else {
                            $last = true;
                            $this->addFlash("info", "You are failed this exam. you will not be certified");
                        }

                        return $this->render('student_quiz/index.html.twig', [
                            'stud_que' => $stud_que,
                            'chapter' => $chapter,
                            'quiz' => $quiz,
                            'last' => $last,
                            'student_quiz' => $student_quiz,
                            'show_un_answered_questions' => $setting['value'],
                            'correct_answer' => $correct_answer
                        ]);
                    } else {
                        if ($quiz_size > 0) {
                            $data = $paginator->paginate(
                                $prev,
                                $request->query->getInt('page', 1),
                                1
                            );

                            return $this->render('instructor_course_chapter/quiz.html.twig', [
                                'quiz' => $quiz,
                                'chapter' => $chapter,
                                'time' => $time,
                                'quiz_ques' => $data,
                                'student_quiz' => $student_quiz,
                            ]);
                        }
                    }
                } else {
                    $stud_que = $em->getRepository(StudentQuestion::class)->find1($quiz->getId(), $this->getUser()->getProfile()->getId());
                    $correct_answer = 0;
                    foreach ($stud_que as $key => $value) {
                        if (strcmp($value->getAnswer(), $value->getQuestion()->getAnswer()) == 0) {
                            $correct_answer++;
                        }
                    }
                    $res = ($correct_answer/sizeof($stud_que))*$quiz->getPercentage();
                    $last_chapter =  $em->getRepository(InstructorCourseChapter::class)->findBy(array('instructorCourse'=>$chapter->getInstructorCourse()->getId()), array('id'=>'DESC'), 1, 0);

                    if ($student_quiz->getResult() == null) {
                        if ($res >= $quiz->getPassValue()) {
                            if (array_key_exists(0, $last_chapter)) {
                                if ($last_chapter[0]->getId() == $chapter->getId()) {
                                    $finalize_course = $stud_course->findOneBy(['student'=>$this->getUser()->getProfile()->getId(),'instructorCourse'=>$chapter->getInstructorCourse()->getId()]) ;
                                    
                                    $origional = $log->changeObjectToArray($finalize_course);

                                    $id = $finalize_course->getId();
                                    $url = $_SERVER['SERVER_NAME']."/certificate/".$this->getUser()->getProfile()->getStudentId()."/".$id;
                                    $finalize_course->setStatus(5);
                                    $finalize_course->setCompletedAt(new DateTime);
                                    $finalize_course->setQrUrl($url);
                                    $finalize_course->setQrCode($this->qrGenerator($url));
                                    $em->persist($finalize_course);
                                    $em->flush();

                                    $modified = $log->changeObjectToArray($finalize_course);
                                    $message = $log->snew($origional, $modified, "update", $this->getUser(), "studentCourse");
                                }
                            }
                        }

                        $origional = $log->changeObjectToArray($student_quiz);

                        $student_quiz->setResult($res);
                        $em->persist($student_quiz);
                        $em->flush();

                        $modified = $log->changeObjectToArray($student_quiz);
                        $message = $log->snew($origional, $modified, "update", $this->getUser(), "studentQuiz");
                    }
                    if ($res >= $quiz->getPassValue()) {
                        if (array_key_exists(0, $last_chapter)) {
                            if ($last_chapter[0]->getId() == $chapter->getId()) {
                                $last = true;
                                $this->addFlash("info", "You are successfully completed this course. you can get your certificate");
                            }
                        }
                    } else {
                        $last = true;
                        $this->addFlash("info", "You are failed this exam. you will not be certified");
                    }

                    return $this->render('student_quiz/index.html.twig', [
                        'stud_que' => $stud_que,
                        'chapter' => $chapter,
                        'quiz' => $quiz,
                        'last' => $last,
                        'student_quiz' => $student_quiz,
                        'show_un_answered_questions' => $setting['value'],
                        'correct_answer' => $correct_answer
                    ]);
                }
            }
        }
        $this->addFlash("danger", "Quiz has not been prepared for this chapter");
        return $this->redirectToRoute("content_list", array('course' => $chapter->getInstructorCourse()->getId(), 'chapter' => $chapter->getTopic()));
    }

    public function qrGenerator($url)
    {
        $qrCode = new QrCode();
        $qrCode
        ->setText($url)
        ->setSize(130)
        ->setPadding(10)
        ->setErrorCorrection('high')
        ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
        ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
        ->setImageType(QrCode::IMAGE_TYPE_PNG);
        return $qrCode->generate();
        // echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />';
    }

    /**
     * @Route("/show/{id}", name="quiz_show", methods={"GET"})
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
    public function edit(Request $request, Quiz $quiz, LogService $log, InstructorRepository $inst_repo): Response
    {
        $this->denyAccessUnlessGranted('quiz_edit');
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $quiz->getInstructorCourseChapter()->getInstructorCourse()->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 
        $chapters = $quiz->getInstructorCourseChapter()->getInstructorCourse()->getInstructorCourseChapters();
        // unset($chapters[$quiz->getInstructorCourseChapter()]);
        $registeredChaptersid = array();
        $unregisteredChaptersid = array();
        $currentIcsid = $quiz->getInstructorCourseChapter()->getId();

        foreach ($chapters as $key => $chapter) {
            $temp = $chapter->getQuizzes();
            foreach ($temp as $qz) {
                if ($qz->getInstructorCourseChapter()->getId() != $currentIcsid) {
                    $registeredChaptersid[] = $qz->getInstructorCourseChapter()->getId();
                }
            }
            $unregisteredChaptersid[] = $chapter->getId();
        }
        $diff = array_diff($unregisteredChaptersid, $registeredChaptersid);

        if (!$diff) {
            $this->addFlash('warning', 'Add chapters firs!!');
            return $this->redirectToRoute('quiz_index', ['id'=>$quiz->getInstructorCourseChapter()->getInstructorCourse()->getId()]);
        }

        $origional = $log->changeObjectToArray($quiz);
        $form = $this->createForm(QuizType::class, $quiz, array('unregisteredChaptersid' => $diff));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($quiz);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "quiz");
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
    public function delete(Request $request, Quiz $quiz, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('quiz_delete');
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $origional = $log->changeObjectToArray($quiz);
                $entityManager->remove($quiz);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), "quiz");
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('quiz_index', ["id"=> $quiz->getInstructorCourseChapter()->getInstructorCourse()->getId()], Response::HTTP_SEE_OTHER);
    }
}
