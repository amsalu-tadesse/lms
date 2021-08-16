<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Form\PermissionType;
use App\Repository\PermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/permission")
 */
class PermissionController extends AbstractController
{
    /**
     * @Route("/", name="permission_index", methods={"GET","POST"})
     */
    public function index(PermissionRepository $permissionRepository,Request $request, PaginatorInterface $paginator): Response
    {

        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $permission=$permissionRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(PermissionType::class, $permission);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('permission_index');
            }

            $queryBuilder=$permissionRepository->findPermission($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('permission/index.html.twig', [
                'permissions' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($permission);
            $entityManager->flush();

            return $this->redirectToRoute('permission_index');
        }
        
        $queryBuilder=$permissionRepository->findPermission($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('permission/index.html.twig', [
            'permissions' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }  
 
    /**
     * @Route("/{id}", name="permission_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Permission $permission): Response
    {
        if ($this->isCsrfTokenValid('delete'.$permission->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($permission);
            $entityManager->flush();
        }

        return $this->redirectToRoute('permission_index');
    }
}
