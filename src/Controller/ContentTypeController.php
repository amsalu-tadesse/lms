<?php

namespace App\Controller;

use App\Entity\ContentType;
use App\Form\ContentTypeType;
use App\Repository\ContentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/content/type")
 */
class ContentTypeController extends AbstractController
{
    /**
     * @Route("/", name="content_type_index", methods={"GET"})
     */
    public function index(ContentTypeRepository $contentTypeRepository): Response
    {
        return $this->render('content_type/index.html.twig', [
            'content_types' => $contentTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="content_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contentType = new ContentType();
        $form = $this->createForm(ContentTypeType::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contentType);
            $entityManager->flush();

            return $this->redirectToRoute('content_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('content_type/new.html.twig', [
            'content_type' => $contentType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="content_type_show", methods={"GET"})
     */
    public function show(ContentType $contentType): Response
    {
        return $this->render('content_type/show.html.twig', [
            'content_type' => $contentType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ContentType $contentType): Response
    {
        $form = $this->createForm(ContentTypeType::class, $contentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('content_type/edit.html.twig', [
            'content_type' => $contentType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="content_type_delete", methods={"POST"})
     */
    public function delete(Request $request, ContentType $contentType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contentType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($contentType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('content_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
