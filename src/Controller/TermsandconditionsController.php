<?php

namespace App\Controller;

use App\Entity\Termsandconditions;
use App\Form\TermsandconditionsType;
use App\Repository\TermsandconditionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/termsandconditions")
 */
class TermsandconditionsController extends AbstractController
{
    /**
     * @Route("/", name="termsandconditions_index", methods={"GET","POST"})
     */
    public function index(TermsandconditionsRepository $termsandconditionsRepository,Request $request, PaginatorInterface $paginator): Response
    {

        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $termsandconditions=$termsandconditionsRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(TermsandconditionsType::class, $termsandconditions);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('termsandconditions_index');
            }

            $queryBuilder=$termsandconditionsRepository->findTermsandconditions($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('termsandconditions/index.html.twig', [
                'termsandconditionss' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $termsandconditions = new Termsandconditions();
        $form = $this->createForm(TermsandconditionsType::class, $termsandconditions);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($termsandconditions);
            $entityManager->flush();

            return $this->redirectToRoute('termsandconditions_index');
        }
        
        $queryBuilder=$termsandconditionsRepository->findTermsandconditions($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('termsandconditions/index.html.twig', [
            'termsandconditionss' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }  
 
    /**
     * @Route("/{id}", name="termsandconditions_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Termsandconditions $termsandconditions): Response
    {
        if ($this->isCsrfTokenValid('delete'.$termsandconditions->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($termsandconditions);
            $entityManager->flush();
        }

        return $this->redirectToRoute('termsandconditions_index');
    }
}
