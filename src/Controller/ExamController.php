<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Form\ExamType;
use App\Repository\ExamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\LogService;

/**
 * @Route("/exam")
 */
class ExamController extends AbstractController
{
    /**
     * @Route("/", name="exam_index", methods={"GET"})
     */
    public function index(ExamRepository $examRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder=$examRepository->findExam($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );

        return $this->render('exam/index.html.twig', [
            'exams' => $data,
        ]);
    }

    /**
     * @Route("/new", name="exam_new", methods={"GET","POST"})
     */
    public function new(Request $request, LogService $log): Response
    {
        $exam = new Exam();
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exam);
            $entityManager->flush();

            $origional = $log->changeObjectToArray($exam);
            $message = $log->snew($origional, "", "create", $this->getUser(), "exam");

            return $this->redirectToRoute('exam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exam/new.html.twig', [
            'exam' => $exam,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="exam_show", methods={"GET"})
     */
    public function show(Exam $exam): Response
    {
        return $this->render('exam/show.html.twig', [
            'exam' => $exam,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="exam_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exam $exam, LogService $log): Response
    {
        $origional = $log->changeObjectToArray($exam);
        $form = $this->createForm(ExamType::class, $exam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $modified = $log->changeObjectToArray($exam);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "exam");

            return $this->redirectToRoute('exam_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exam/edit.html.twig', [
            'exam' => $exam,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="exam_delete", methods={"POST"})
     */
    public function delete(Request $request, Exam $exam, LogService $log): Response
    {
        $origional = $log->changeObjectToArray($exam);

        if ($this->isCsrfTokenValid('delete'.$exam->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exam);
            $entityManager->flush();
            $message = $log->snew($origional, "", "delete", $this->getUser(), "exam");
        }

        return $this->redirectToRoute('exam_index', [], Response::HTTP_SEE_OTHER);
    }
}
