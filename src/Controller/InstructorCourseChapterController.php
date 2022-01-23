<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseChapter;
use App\Form\InstructorCourseChapterType;
use App\Repository\InstructorCourseRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\LogService;
use App\Repository\InstructorRepository;

/**
 * @Route("/mycourses")
 */
class InstructorCourseChapterController extends AbstractController
{
    /**
     * @Route("/", name="instructor_course_chapter_index", methods={"GET"})
     */
    public function index(InstructorCourseRepository $instructorCourseRepository): Response
    {
        $em = $this->getDoctrine()->getManager();

        // $teachersList = $em->getRepository(Instructor::class)->findAll();
        //    dd($instructorCourseRepository->findAll());
        $mylist = $em->getRepository(InstructorCourse::class)->findByUser($this->getUser());

        return $this->render('instructor_course_chapter/instructor_view.html.twig', [
            'instructor_courses' => $mylist,
            // 'instructorsList' => $teachersList,
        ]);
    }

    /**
     * @Route("/chapters/{id}", name="instructor_course_chapter_content_list", methods={"GET"})
     */
    public function contentList(InstructorCourse $instructorCourse, Request $request,InstructorRepository $inst_repo): Response
    {
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $instructorCourse->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 

        $em = $this->getDoctrine()->getManager();
        $instructorCourseChapters = $em->getRepository(InstructorCourseChapter::class)->findBy(['instructorCourse' => $instructorCourse]);
        return $this->render('instructor_course_chapter/index.html.twig', [
            'instructor_course_chapters' => $instructorCourseChapters,
            'instructor_course' => $instructorCourse,
            //  'incrsid' => $request->get('id'),
        ]);
    }

    /**
     * @Route("/quiz/{id}", name="quiz", methods={"GET"})
     */
    public function quiz(InstructorCourse $instructorCourse, Request $request): Response
    {
        $this->denyAccessUnlessGranted('quiz_list');
        $em = $this->getDoctrine()->getManager();
        $instructorCourseChapters = $em->getRepository(InstructorCourseChapter::class)->findBy(['instructorCourse' => $instructorCourse]);
        return $this->render('quiz/index.html.twig', [
            'instructor_course_chapters' => $instructorCourseChapters,
            'instructor_course' => $instructorCourse,
            //  'incrsid' => $request->get('id'),
        ]);
    }

    /**
     * @Route("/new/{id}", name="instructor_course_chapter_new", methods={"GET","POST"})
     */
    public function new(Request $request, InstructorCourse $instructorCourse, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('chapter_create');
        $incrsid = $request->get('id');
        $instructorCourseChapter = new InstructorCourseChapter();
        $form = $this->createForm(InstructorCourseChapterType::class, $instructorCourseChapter);
        $form->handleRequest($request);
        $instructorCourseChapter->setCreatedAt(new DateTime());
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $instructorCourseChapter->setInstructorCourse($instructorCourse);
            $entityManager->persist($instructorCourseChapter);
            $entityManager->flush();
            
            $origional = $log->changeObjectToArray($instructorCourseChapter);
            $message = $log->snew($origional, "", "create", $this->getUser(), "instructorCourseChapter");

            return $this->redirectToRoute('instructor_course_chapter_content_list', ['id' => $incrsid], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('instructor_course_chapter/new.html.twig', [
            'instructor_course_chapter' => $instructorCourseChapter,
            'form' => $form,
            'id' => $instructorCourse->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="instructor_course_chapter_show", methods={"GET"})
     */
    public function show(InstructorCourseChapter $instructorCourseChapter): Response
    {
        $this->denyAccessUnlessGranted('chapter_list');
        return $this->render('instructor_course_chapter/show.html.twig', [
            'instructor_course_chapter' => $instructorCourseChapter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="instructor_course_chapter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, InstructorCourseChapter $instructorCourseChapter, LogService $log, InstructorRepository $inst_repo): Response
    {
        $this->denyAccessUnlessGranted('chapter_edit');

        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $instructorCourseChapter->getInstructorCourse()->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 

        $origional = $log->changeObjectToArray($instructorCourseChapter);
        $form = $this->createForm(InstructorCourseChapterType::class, $instructorCourseChapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($instructorCourseChapter);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "instructorCourseChapter");
            return $this->redirectToRoute('instructor_course_chapter_content_list', ['id'=> $instructorCourseChapter->getInstructorCourse()->getId()]);
        }

        return $this->renderForm('instructor_course_chapter/edit.html.twig', [
            'instructor_course_chapter' => $instructorCourseChapter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="instructor_course_chapter_delete", methods={"POST"})
     */
    public function delete(Request $request, InstructorCourseChapter $instructorCourseChapter, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('chapter_delete');
        if ($this->isCsrfTokenValid('delete' . $instructorCourseChapter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $origional = $log->changeObjectToArray($instructorCourseChapter);

                $entityManager->remove($instructorCourseChapter);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), "instructorCourseChapter");

            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('instructor_course_chapter_index', [], Response::HTTP_SEE_OTHER);
    }
}
