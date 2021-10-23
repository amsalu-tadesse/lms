<?php

namespace App\Controller;

use App\Entity\AcademicLevel;
use App\Form\AcademicLevelType;
use App\Repository\AcademicLevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/academic/level")
 */
class AcademicLevelController extends AbstractController
{
    /**
     * @Route("/", name="academic_level_index", methods={"GET"})
     */
    public function index(AcademicLevelRepository $academicLevelRepository): Response
    {
        return $this->render('academic_level/index.html.twig', [
            'academic_levels' => $academicLevelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="academic_level_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $academicLevel = new AcademicLevel();
        $form = $this->createForm(AcademicLevelType::class, $academicLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($academicLevel);
            $entityManager->flush();

            return $this->redirectToRoute('academic_level_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('academic_level/new.html.twig', [
            'academic_level' => $academicLevel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="academic_level_show", methods={"GET"})
     */
    public function show(AcademicLevel $academicLevel): Response
    {
        return $this->render('academic_level/show.html.twig', [
            'academic_level' => $academicLevel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="academic_level_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AcademicLevel $academicLevel): Response
    {
        $form = $this->createForm(AcademicLevelType::class, $academicLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('academic_level_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('academic_level/edit.html.twig', [
            'academic_level' => $academicLevel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="academic_level_delete", methods={"POST"})
     */
    public function delete(Request $request, AcademicLevel $academicLevel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$academicLevel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($academicLevel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('academic_level_index', [], Response::HTTP_SEE_OTHER);
    }
}
