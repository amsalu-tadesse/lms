<?php

namespace App\Controller;

use App\Entity\Termsandconditions;
use App\Form\Termsandconditions1Type;
use App\Repository\TermsandconditionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/termsandconditions")
 */
class TermsandconditionsController extends AbstractController
{
    /**
     * @Route("/", name="termsandconditions_index", methods={"GET"})
     */
    public function index(TermsandconditionsRepository $termsandconditionsRepository): Response
    {
        return $this->render('termsandconditions/index.html.twig', [
            'termsandconditions' => $termsandconditionsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="termsandconditions_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $termsandcondition = new Termsandconditions();
        $form = $this->createForm(Termsandconditions1Type::class, $termsandcondition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($termsandcondition);
            $entityManager->flush();

            return $this->redirectToRoute('termsandconditions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('termsandconditions/new.html.twig', [
            'termsandcondition' => $termsandcondition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="termsandconditions_show", methods={"GET"})
     */
    public function show(Termsandconditions $termsandcondition): Response
    {
        return $this->render('termsandconditions/show.html.twig', [
            'termsandcondition' => $termsandcondition,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="termsandconditions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Termsandconditions $termsandcondition): Response
    {
        $form = $this->createForm(Termsandconditions1Type::class, $termsandcondition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('termsandconditions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('termsandconditions/edit.html.twig', [
            'termsandcondition' => $termsandcondition,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="termsandconditions_delete", methods={"POST"})
     */
    public function delete(Request $request, Termsandconditions $termsandcondition): Response
    {
        if ($this->isCsrfTokenValid('delete'.$termsandcondition->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($termsandcondition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('termsandconditions_index', [], Response::HTTP_SEE_OTHER);
    }
}
