<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\InstructorCourse;
use App\Entity\Course;
use App\Entity\InstructorCourseStatus;
use App\Form\Filter\InstructorCourseFilterType;
use App\Form\InstructorCourseType;
use App\Repository\InstructorCourseRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\LogService;

/**
 * @Route("/instructor/course")
 */
class InstructorCourseController extends AbstractController
{
    /**
     * @Route("/getTeachers", name="instructors_for_course", methods={"POST"})
     */
    public function indexf(Request $request, InstructorCourseRepository $instructorCourseRepository): Response
    {
        if($request->isXmlHttpRequest()) {
            $result = array();
            $id = $request->request->get("id");
            $instructors = $instructorCourseRepository->getInstructorsForCourse($id);
            
            $returnResponse = new JsonResponse();
            $returnResponse->setJson(json_encode($instructors));
            return $returnResponse;
        }
        else{
            die("error");
        } 
    }

    /**
     * @Route("/", name="instructor_course_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator, InstructorCourseRepository $instructorCourseRepository): Response
    {
        $this->denyAccessUnlessGranted('instructor_course_list');
        $pageSize = 15;
        $em = $this->getDoctrine()->getManager();

        $teachersList = $em->getRepository(Instructor::class)->findAll();

        $st_course = new InstructorCourse();
        $searchForm = $this->createForm(InstructorCourseFilterType::class, $st_course);
        $searchForm->handleRequest($request);

        $queryBuilder = $instructorCourseRepository->filterIC($request->query->get('course'), $request->query->get('instructor'));

        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $pageSize
        );
        $crs_stnumber = array();
        $ic_student_count = $em->getRepository(instructorCourse::class)->findAll();
        $json = "[['Course', 'number']";

        foreach ($ic_student_count as $key => $value) {
            $crs_stnumber[$value->getCourse()->getName()] =  sizeof($value->getStudentCourses());

            $col =  ",['".$value->getCourse()->getName()."', ".sizeof($value->getStudentCourses())."]";
            $col =trim(preg_replace('/\s+/', ' ', $col));
            $json .= $col ;
        }
        $json .= "];";
        return $this->render('instructor_course/index.html.twig', [
            'instructor_courses' => $data,
            'instructorsList' => $teachersList,
            'searchForm' => $searchForm->createView(),
            'crs_stnumber' =>$json ,

        ]);
    }
    

    /**
     * @Route("/new", name="instructor_course_new", methods={"GET","POST"})
     */
    function new (Request $request, LogService $log): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $instructorCourse1 = new InstructorCourse();
        
        $course = $entityManager->getRepository(Course::class)->findAll();
        $instructorCourse = $entityManager->getRepository(InstructorCourse::class)->findBy(array('active'=>1));
        
        $free_courses = array();
        foreach($course as $key => $value)
        {
            $flag = true;
            foreach($instructorCourse as $key1 => $value1)
            {   
                if($value->getId() == $value1->getCourse()->getId())
                    $flag = false;
            }

            if($flag) $free_courses[] = $value->getId(); 
        }
        $form = $this->createForm(InstructorCourseType::class, $instructorCourse1, array('courses'=> $free_courses));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $instructorCourseStatus = $entityManager->getRepository(InstructorCourseStatus::class)->find(2); //assigned but waiting
            $instructorCourse1->setStatus($instructorCourseStatus);
            $instructorCourse1->setCreatedAt(new DateTime());
            $entityManager->persist($instructorCourse1);
            $entityManager->flush();

            $origional = $log->changeObjectToArray($instructorCourse1);
            $message = $log->snew($origional, "", "create", $this->getUser(), "instructorCourse");

            return $this->redirectToRoute('instructor_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instructor_course/new.html.twig', [
            'instructor_course' => $instructorCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/assign_instructor/{id}", name="assign_instructor", methods={"GET","POST"})
     */
    public function assignInstructor(Request $request, InstructorCourse $instructorCourse, LogService $log)
    {
        $this->denyAccessUnlessGranted('instructor_course_assign');

        $origional = $log->changeObjectToArray($instructorCourse);
            
        $em = $this->getDoctrine()->getManager();
        $instId = $request->request->get("instructor");
        $instructor = $em->getRepository(Instructor::class)->find($instId);
        $instructorCourse->setInstructor($instructor);
        $instructorCourseStatus = $em->getRepository(InstructorCourseStatus::class)->find(2);
        $instructorCourse->setStatus($instructorCourseStatus);
        $em->flush();

        $modified = $log->changeObjectToArray($instructorCourse);
        $message = $log->snew($origional, $modified, "update", $this->getUser(), "instructorCourse");

        return $this->redirectToRoute("instructor_course_index");
    }

    /**
     * @Route("/{id}", name="instructor_course_show", methods={"GET"})
     */
    public function show(InstructorCourse $instructorCourse): Response
    {
        $this->denyAccessUnlessGranted('instructor_course_list');
        return $this->render('instructor_course/show.html.twig', [
            'instructor_course' => $instructorCourse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="instructor_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InstructorCourse $instructorCourse, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('instructor_course_edit');

        $origional = $log->changeObjectToArray($instructorCourse);

        $form = $this->createForm(InstructorCourseType::class, $instructorCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($instructorCourse);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "instructorCourse");
            return $this->redirectToRoute('instructor_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instructor_course/edit.html.twig', [
            'instructor_course' => $instructorCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="instructor_course_delete", methods={"POST"})
     */
    public function delete(Request $request, InstructorCourse $instructorCourse, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('instructor_course_delete');
        if ($this->isCsrfTokenValid('delete' . $instructorCourse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            try {
                $origional = $log->changeObjectToArray($instructorCourse);
                $entityManager->remove($instructorCourse);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), "instructorCourse");
            } catch (\Exception $ex) {
                dd($ex);
            }
            catch(\Exception $ex)
            {
                dd($ex);
            }
        }

        return $this->redirectToRoute('instructor_course_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/course/diactivate/{id}", name="instructor_course_deactivate", methods={"GET","POST"})
     */
    public function instructorCourseDeactivate(InstructorCourse $instructorCourse, Request $request, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('instructor_course_deactivate');
        $origional = $log->changeObjectToArray($instructorCourse);
        $em = $this->getDoctrine()->getManager();
        if ($instructorCourse->getActive()) {
            $instructorCourse->setActive(false);
        } else {
            $em = $this->getDoctrine()->getManager();
            $check = $em->getRepository(InstructorCourse::class)->findBy(array('course'=>$instructorCourse->getCourse()->getId(), 'active'=>1));
            if($check){
                $this->addFlash('danger',"This course is Active you should deactivate another course assignment before");
            }
            else{
                $instructorCourse->setActive(true);
                $modified = $log->changeObjectToArray($instructorCourse);
                $message = $log->snew($origional, $modified, "update", $this->getUser(), "instructorCourse");
            }
        }
        $em->flush();

        return $this->redirectToRoute('instructor_course_index');
    }
}
