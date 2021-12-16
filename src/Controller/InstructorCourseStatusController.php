<?php

namespace App\Controller;

use App\Entity\InstructorCourseStatus;
use App\Form\InstructorCourseStatusType;
use App\Repository\InstructorCourseStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/instructorCourseStatus")
 */
class InstructorCourseStatusController extends AbstractController
{
    /**
     * @Route("/", name="instructorCourseStatus_index", methods={"GET","POST"})
     */
    public function index(InstructorCourseStatusRepository $instructorCourseStatusRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->request->get('edit')) {
            $id=$request->request->get('edit');
            $instructorCourseStatus=$instructorCourseStatusRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(InstructorCourseStatusType::class, $instructorCourseStatus);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('instructorCourseStatus_index');
            }

            $queryBuilder=$instructorCourseStatusRepository->findInstructorCourseStatus($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('instructor_course_status/index.html.twig', [
                'instructorCourseStatuss' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);
        }
        $instructorCourseStatus = new InstructorCourseStatus();
        $form = $this->createForm(InstructorCourseStatusType::class, $instructorCourseStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($instructorCourseStatus);
            $entityManager->flush();

            return $this->redirectToRoute('instructorCourseStatus_index');
        }

        $queryBuilder=$instructorCourseStatusRepository->findInstructorCourseStatus($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('instructor_course_status/index.html.twig', [
            'instructorCourseStatuss' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }

    /**
     * @Route("/{id}", name="instructorCourseStatus_delete", methods={"DELETE"})
     */
    public function delete(Request $request, InstructorCourseStatus $instructorCourseStatus): Response
    {
        if ($this->isCsrfTokenValid('delete'.$instructorCourseStatus->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($instructorCourseStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('instructorCourseStatus_index');
    }
}
