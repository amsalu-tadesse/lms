<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\StudentCourse;
use App\Form\StudentCourseType;
use App\Repository\StudentCourseRepository;
use App\Repository\ContentRepository;
use App\Repository\StudentChapterRepository;
use App\Repository\InstructorCourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @Route("/student")
 */
class StudentCourseController extends AbstractController
{
    /**
     * @Route("/", name="student_course_index", methods={"GET"})
     */

    // ******** student home page , don't touch it OK
    public function index(StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request, InstructorCourseRepository $course): Response
    {
       
       if($this->getUser()->getProfile() == null)
        {
            return $this->redirectToRoute('app_login');
        }

        $queryBuilder=$studentCourseRepository->findCourses($this->getUser()->getProfile()->getId());
        $courses = $course->findCoursesSortByCategory();
        
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('student_course/student.html.twig', [
            'student_courses' => $data,
            'courses' => $courses
        ]);
    }


    /**
     * @Route("/course/request", name="course_request", methods={"GET"})
     */
    public function courseRequest(Request $request, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator): Response
    {
         
        // $em = $this->getDoctrine()->getManager();
        // $queryBuilder = $studentCourseRepository->findBy(['status'=>0, 'active'=>1]);
        $queryBuilder = $studentCourseRepository->findAll();

        $stdCrs=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('student_course/course_requests.html.twig', [
            'student_courses' => $stdCrs,
         
        ]);
    }

    /**
     * @Route("/course/request/{id}", name="course_request_deactivate", methods={"GET","POST"})
     */
    public function courseRequestDeactivate(StudentCourse $studentCourse, StudentCourseRepository $studentCourseRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        

        $studentCourse->setStatus(1);//accepted
        $em->flush();
        
   
    // $queryBuilder = $studentCourseRepository->findBy(['status'=>0, 'active'=>1]);
    $queryBuilder = $studentCourseRepository->findAll();

    $stdCrs=$paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        10
    );
    
    return $this->redirectToRoute('course_request');
       
/*
        return $this->render('student_course/course_requests.html.twig', [
            'student_courses' => $stdCrs,
         ]);*/
        
    }




    /**
     * @Route("/course/{id}", name="students_in_course", methods={"GET"})
     */

    public function listStudentUnderCourse(StudentCourseRepository $studentCourseRepository, InstructorCourse $instructorCourse, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder=$studentCourseRepository->findBy(['instructorCourse'=>$instructorCourse]);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('student_course/students_in_course.html.twig', [
            'student_courses' => $data,
            'instructor_course' => $instructorCourse,
        ]);
    }

    /**
     * @Route("/apply", name="course_apply")
     */
    public function apply(Request $request): Response
    {
        $courses = $request->cookies->get("selected_courses_login");
        $courses = json_decode($courses, true);
        $em = $this->getDoctrine()->getManager();
        foreach($courses as $course)
        {
            $st_course = new StudentCourse();
            $st_course->setStudent($this->getUser()->getProfile());
            $st_course->setInstructorCourse($em->getRepository(InstructorCourse::class)->find($course));
            $st_course->setStatus(0);
            $st_course->setActive(0);
            $st_course->setCreatedAt(new DateTime());
            $em->persist($st_course);
            $em->flush();
        }
        //write form data to cookie
        $response = new Response();
        $cookie = new Cookie('selected_courses_login', "" ,time()*60*60);
        $response->headers->setCookie($cookie);
        $response->sendHeaders();

        return $this->redirectToRoute("requested_courses");
    }
    
     /**
     * @Route("/request", name="requested_courses", methods={"GET"})
     */
    public function requests(StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder=$studentCourseRepository->findRequestedCourses($this->getUser()->getProfile()->getId());

        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            8
        );
        
        return $this->render('student_course/requested_courses.html.twig', [
            'requests' => $data,
        ]);
    }


    /**
     * @Route("/udpate/{chapter}/counter", name="update_counter")
     */
    public function updater($chapter, StudentChapterRepository $student_chapter)
    {
        
        $em = $this->getDoctrine()->getManager();
        $stud_chap = $student_chapter->getProgress1($chapter, $this->getUser()->getProfile()->getId());
        $conn = $this->getDoctrine()->getManager()
            ->getConnection();
        $sql = "update student_chapter set pages_completed = pages_completed+1 where student_id = :student and id = :chapter" ;
        $stmt = $conn->prepare($sql);

        $stmt->execute(array("student"=>$this->getUser()->getProfile()->getId(), "chapter"=>$chapter));
        $returnResponse = new JsonResponse();
        $returnResponse->setJson("success");
    
        return $returnResponse;
    }

    /**
    * @Route("/request/{id}", name="request_show", methods={"GET"})
    */
    public function requestShow($id, StudentCourseRepository $studentCourseRepository, ContentRepository $content): Response
    {
        $request = $studentCourseRepository->findRequest($this->getUser()->getProfile()->getId(), $id);

        $chaptersWithContent = $content->getChaptersWithContentForCourse($request['instructor_course_id']);

        return $this->render('student_course/request_show.html.twig', [
            'courses' => $request,
            'chapters' => $chaptersWithContent
        ]);
    }

    /**
     * @Route("/course/diactivate/{id}", name="student_course_deactivate", methods={"GET","POST"})
     */
    public function studentCourseDeactivate(StudentCourse $studentCourse, StudentCourseRepository $studentCourseRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($studentCourse->getActive()) {
            $studentCourse->setActive(false);
        } else {
            $studentCourse->setActive(true);
        }
        $em->flush();
        

        $queryBuilder=$studentCourseRepository->findBy(['instructorCourse'=>$studentCourse->getInstructorCourse()]);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('student_course/students_in_course.html.twig', [
            'student_courses' => $data,
            'instructor_course' => $studentCourse->getInstructorCourse(),
        ]);
        
    }


    




    /**
     * @Route("/new", name="student_course_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $studentCourse = new StudentCourse();
        $form = $this->createForm(StudentCourseType::class, $studentCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studentCourse);
            $entityManager->flush();

            return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_course/new.html.twig', [
            'student_course' => $studentCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_show", methods={"GET"})
     */
    public function show(StudentCourse $studentCourse): Response
    {
        return $this->render('student_course/show.html.twig', [
            'student_course' => $studentCourse,
        ]);
    }

    /**
     *  @Route("/{id}/remove", name="request_delete")
     */
    public function removeRequest(StudentCourseRepository $studentCourse, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $studentCourse = $entityManager->getRepository(StudentCourse::class)->find($id);
        $entityManager->remove($studentCourse);
        $entityManager->flush();

        return $this->redirectToRoute("requested_courses");
    }

    // /**
    //  * @Route("/{id}/remove", name="request_delete")
    //  */
    // public function deleteRequest(StudentCourseRepository $studentCourse, $id): Response
    // { 
    //     $course = $studentCourse->find($id);
    //     $em = $this->getDoctrine()->getManager();
    //     $em->remove($course);
    //     $em->flush();
    
    //     // Send all this stuff back to DataTables
    //     $returnResponse = new JsonResponse();
    //     $returnResponse->setJson("kkd");
    
    //     return $returnResponse;
    // }

    /**
     * @Route("/home", methods={"GET"})
     */
    public function student()
    {
        return $this->render('student_course/student.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="student_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StudentCourse $studentCourse): Response
    {
        //dont use this section, it is not usefull.
        $form = $this->createForm(StudentCourseType::class, $studentCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_course/edit.html.twig', [
            'student_course' => $studentCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_delete", methods={"POST"})
     */
    public function delete(Request $request, StudentCourse $studentCourse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studentCourse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($studentCourse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
