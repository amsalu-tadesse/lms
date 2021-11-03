<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseChapter;
use App\Entity\InstructorCourseStatus;
use App\Form\CourseType;
use App\Form\QuestionAnswerNewStudentType;
use App\Entity\QuestionAnswer;
use App\Repository\CourseRepository;
use App\Repository\InstructorCourseRepository;
use App\Repository\StudentQuizRepository;
use DateTime;
use App\Repository\ContentRepository;
use App\Repository\InstructorCourseChapterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET","POST"})
     */
    public function index(CourseRepository $courseRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $em = $this->getDoctrine()->getManager();
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $course=$courseRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(CourseType::class, $course);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                //$instructorCourse = $em->getRepository(InstructorCourse::class)->findOneBy(['course'=>$course,'active'=>1]);//assumption only one inst for one active inst course.
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('course_index');
            }

            $queryBuilder=$courseRepository->findCourse($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('course/index.html.twig', [
                'courses' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setStatus(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
            $instructorCourse = new InstructorCourse();
            $instructorCourse->setCourse($course);
            $instructorCourseStatus = $em->getRepository(InstructorCourseStatus::class)->find(1);//not assigned
            $instructorCourse->setStatus($instructorCourseStatus);
            $instructorCourse->setCreatedAt(new DateTime());
            $instructorCourse->setActive(true);
            $entityManager->persist($instructorCourse);
            $entityManager->flush();

            return $this->redirectToRoute('course_index');
        }
        
        $queryBuilder=$courseRepository->findCourse($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('course/index.html.twig', [
            'courses' => $data,
            'form' => $form->createView(), 
            'edit'=>false
        ]);
    }  



     /**
     * @Route("/detail/{id}", name="course_description")
     */
    public function courseDetail(InstructorCourseChapter $chapter, InstructorCourseRepository $course, ContentRepository $content, Request $request): Response
    {
        $courses = $course->findCoursesSortByCategory($chapter->getId());
        $chaptersWithContent = $content->getChaptersWithContentForCourse($chapter->getId());
        
        if($this->isGranted("ROLE_STUDENT"))
        {
            $questionAnswer = new QuestionAnswer();
            $form = $this->createForm(QuestionAnswerNewStudentType::class, $questionAnswer);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid())
            {
                $questionAnswer->setStudent($this->getUser()->getProfile());
                $questionAnswer->setQuestion($form['question']->getData());
                $questionAnswer->setCreatedAt(new DateTime());
                $questionAnswer->setIsReply(0);
                $questionAnswer->setCourse($course->find($request->request->get("val1")));
                $em = $this->getDoctrine()->getManager();
                $em->persist($questionAnswer);
                $em->flush();
            }
    
            $em = $this->getDoctrine()->getManager();
            $question = $em->getRepository(QuestionAnswer::class)->findBy(['course'=>$chapter->getId()],['id'=>'desc']);
    
            return $this->render('course/description_login.html.twig',[
                'chapter' => $chapter,
                'chapters' => $chaptersWithContent,
                'question' => $question,
                'form' => $form->createView()
            ]);   
        }

       
        
        return $this->render('course/description.html.twig',[
            'chapter' => $chapter,
            'chapters' => $chaptersWithContent
        ]);
    }

    /**
     * @Route("/mycourses", name="selected_courses")
     */
    public function selectedCourses(Request $request, CourseRepository $course): Response
    {
        if($this->isGranted("ROLE_STUDENT")){
            $selected_courses = $request->cookies->get("selected_courses_login");
            $selected_courses = json_decode($selected_courses, true);

            $em = $this->getDoctrine()->getManager();
            $courses = $em->getRepository(Course::class)->findBy(array('id' => $selected_courses));
            return $this->render('student_course/selected_courses_login.html.twig',[
                'courses' => $courses
            ]);
        }

        $selected_courses = $request->cookies->get("selected_courses");
        $selected_courses = json_decode($selected_courses, true);

        $em = $this->getDoctrine()->getManager();
        $courses = $em->getRepository(Course::class)->findBy(array('id' => $selected_courses));
        return $this->render('course/selected_courses.html.twig',[
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/remove", name="remove_selected")
     */
    public function removeSelected()
    {
        $response = new Response();
        $response->headers->clearCookie('selected_courses');
        $response->send();
        dd("done");
    }

    /**
     * @Route("/{id}/chapters/", name="course_chapters", methods={"GET"})
     */
    public function chapters(InstructorCourse $course, StudentQuizRepository $student_quiz, ContentRepository $contentRepository, InstructorCourseChapterRepository $chaptersRepository): Response
    {   
        $chapters = $chaptersRepository->findChapters($course->getId(), $this->getUser()->getProfile()->getId());  
        $contents = $contentRepository->getContentsCount($course->getId());
        $chapter_list = array();

        foreach($chapters as $key => $value){
            $flag = 1;
            $chapter_list[$key] = $value[0];
            foreach($contents as $key1 => $value1)
            {
                if($value[0]['id'] == $value1['id']){
                    // $chapters[$key] = $value
                    $chapter_list[$key]['total_video'] = $value1['total_video'];
                    $chapter_list[$key]['total_content'] = $value1['con'];
                    $total_content = $value1['total_video']+$value1['con'];
                    $chapter_list[$key]['completed'] = ($value['pagesCompleted']/$total_content)*100;
                    $flag = 2;
                break;
                }
            }

            if($flag == 1){
                $chapter_list[$key]['total_video'] = 0;
                $chapter_list[$key]['total_content'] = 0;
                $chapter_list[$key]['completed'] = 0;
            }

        }

        $quiz = $student_quiz->getQuizesForStudent($course->getId(), $this->getUser()->getProfile()->getId());
        $quizWithChapter = array();
        foreach($quiz as $key => $value)
        {
            $quizWithChapter[$value['chapter_id']] =  $value;
        }
        return $this->render('student_course/chapters.html.twig',[
            'chapters' => $chapter_list,
            'course' => $course->getCourse(),
            'contents' => $contents,
            'quiz' => $quizWithChapter 
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
}