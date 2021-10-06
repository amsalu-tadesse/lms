<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseStatus;
use App\Form\CourseType;
use App\Repository\CourseRepository;
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
     * @Route("/{id}/chapters/", name="course_chapters", methods={"GET"})
     */
    public function chapters($id, ContentRepository $contentRepository, InstructorCourseChapterRepository $chaptersRepository): Response
    {
        $chapters = $chaptersRepository->findChaptersInCourse($id);
        $contents = $contentRepository->getContentsCount($id);

        dd($contents);
        return $this->render('student_course/chapters.html.twig',[
            'chapters' => $chapters,
            'contents' => $contents,
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