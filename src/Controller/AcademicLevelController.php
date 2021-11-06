<?php

namespace App\Controller;

use App\Entity\AcademicLevel;
use App\Form\AcademicLevelType;
use App\Repository\AcademicLevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/academiclevel")
 */
class AcademicLevelController extends AbstractController
{
    /**
     * @Route("/", name="academic_level_index", methods={"GET"})
     */
    public function index(AcademicLevelRepository $academicLevelRepository, Request $request, PaginatorInterface $paginator): Response
    {
        //  $this->denyAccessUnlessGranted('academic_level_list');
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $academiclevel=$academiclevelRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(AcademicLevelType::class, $academiclevel);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('academiclevel_index');
            }

            $queryBuilder=$academiclevelRepository->findAcademicLevel($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('user_type/index.html.twig', [
                'academiclevels' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $academiclevel = new AcademicLevel();
        $form = $this->createForm(AcademicLevelType::class, $academiclevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($academiclevel);
            $entityManager->flush();

            return $this->redirectToRoute('academiclevel_index');
        }

        $queryBuilder=$academicLevelRepository->findAcademicLevel($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('academic_level/index.html.twig', [
            'academiclevels' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }

    /**
     * @Route("/new", name="academic_level_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('academic_level_create');
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
        $this->denyAccessUnlessGranted('academic_level_edit');
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
        $this->denyAccessUnlessGranted('academic_level_delete');
        if ($this->isCsrfTokenValid('delete'.$academicLevel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
          

            try
            {
                $entityManager->remove($academicLevel);
                $entityManager->flush();
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger',$message );
            }

        }

        return $this->redirectToRoute('academic_level_index', [], Response::HTTP_SEE_OTHER);
    }
}
