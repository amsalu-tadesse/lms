<?php

namespace App\Controller;

use App\Entity\CourseCategory;
use App\Form\CourseCategoryType;
use App\Controller\UtilityController;
use App\Repository\CourseCategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\LogService;

/**
 * @Route("/course/category")
 */
class CourseCategoryController extends AbstractController
{
    /**
     * @Route("/", name="course_category_index", methods={"GET"})
     */
    public function index(CourseCategoryRepository $courseCategoryRepository): Response
    {
        $this->denyAccessUnlessGranted('course_category_list');
        return $this->render('course_category/index.html.twig', [
            'course_categories' => $courseCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="course_category_new", methods={"GET","POST"})
     */
    public function new(Request $request, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('course_category_create');
        $courseCategory = new CourseCategory();
        $form = $this->createForm(CourseCategoryType::class, $courseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($courseCategory);
            $entityManager->flush();
            
            $origional = $log->changeObjectToArray($courseCategory);

            $message = $log->snew($origional, "", "create", $this->getUser(), "courseCategory");
            if(!$message)
            $this->addFlash("info", "Log not created");


            return $this->redirectToRoute('course_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course_category/new.html.twig', [
            'course_category' => $courseCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="course_category_show", methods={"GET"})
     */
    public function show(CourseCategory $courseCategory): Response
    {
        $this->denyAccessUnlessGranted('course_category_list');
        return $this->render('course_category/show.html.twig', [
            'course_category' => $courseCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="course_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CourseCategory $courseCategory, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('course_category_edit');

        $origional = $log->changeObjectToArray($courseCategory);
        $form = $this->createForm(CourseCategoryType::class, $courseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($courseCategory);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "courseCategory");
            return $this->redirectToRoute('course_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course_category/edit.html.twig', [
            'course_category' => $courseCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="course_category_delete", methods={"POST"})
     */
    public function delete(Request $request, CourseCategory $courseCategory, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('course_category_delete');
        if ($this->isCsrfTokenValid('delete' . $courseCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $origional = $log->changeObjectToArray($courseCategory);
                $entityManager->remove($courseCategory);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), 'courseCategory');
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('course_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
