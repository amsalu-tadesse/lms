<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\InstructorCourse;
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

/**
 * @Route("/instructor/course")
 */
class InstructorCourseController extends AbstractController
{
    /**
     * @Route("/", name="instructor_course_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator, InstructorCourseRepository $instructorCourseRepository): Response
    {
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
    // dd($json);
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
    function new (Request $request): Response {
        $instructorCourse = new InstructorCourse();
        $form = $this->createForm(InstructorCourseType::class, $instructorCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $instructorCourseStatus = $entityManager->getRepository(InstructorCourseStatus::class)->find(2); //assigned but waiting
            $instructorCourse->setStatus($instructorCourseStatus);
            $instructorCourse->setCreatedAt(new DateTime());
            $entityManager->persist($instructorCourse);
            $entityManager->flush();

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
    public function assignInstructor(Request $request, InstructorCourse $instructorCourse)
    {
        $em = $this->getDoctrine()->getManager();
        $instId = $request->request->get("instructor");
        $instructor = $em->getRepository(Instructor::class)->find($instId);
        $instructorCourse->setInstructor($instructor);
        $instructorCourseStatus = $em->getRepository(InstructorCourseStatus::class)->find(2);
        $instructorCourse->setStatus($instructorCourseStatus);
        $em->flush();

        return $this->redirectToRoute("instructor_course_index");
    }

    /**
     * @Route("/{id}", name="instructor_course_show", methods={"GET"})
     */
    public function show(InstructorCourse $instructorCourse): Response
    {
        return $this->render('instructor_course/show.html.twig', [
            'instructor_course' => $instructorCourse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="instructor_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InstructorCourse $instructorCourse): Response
    {
        $form = $this->createForm(InstructorCourseType::class, $instructorCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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
    public function delete(Request $request, InstructorCourse $instructorCourse): Response
    {
        if ($this->isCsrfTokenValid('delete' . $instructorCourse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            try
            {
                $entityManager->remove($instructorCourse);
                $entityManager->flush();
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
    public function instructorCourseDeactivate(InstructorCourse $instructorCourse, InstructorCourseRepository $instructorCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($instructorCourse->getActive()) {
            $instructorCourse->setActive(false);
        } else {
            $instructorCourse->setActive(true);
        }
        $em->flush();
        return $this->redirectToRoute('instructor_course_index');

    }
}
