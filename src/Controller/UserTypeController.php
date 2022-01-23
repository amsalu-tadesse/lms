<?php

namespace App\Controller;

use App\Entity\UserType;
use App\Form\UserTypeType;
use App\Repository\UserTypeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\LogService;

/**
 * @Route("/usertype")
 */
class UserTypeController extends AbstractController
{
    /**
     * @Route("/", name="usertype_index", methods={"GET","POST"})
     */
    public function index(UserTypeRepository $usertypeRepository, Request $request, PaginatorInterface $paginator, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('user_type_list');

        if ($request->request->get('edit') ) {
            $this->denyAccessUnlessGranted('user_type_edit');
            $id = $request->request->get('edit');
            $usertype = $usertypeRepository->findOneBy(['id' => $id]);
            $origional = $log->changeObjectToArray($usertype);
            $form = $this->createForm(UserTypeType::class, $usertype);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $modified = $log->changeObjectToArray($usertype);
                $message = $log->snew($origional, $modified, "update", $this->getUser(), "userType");
                return $this->redirectToRoute('usertype_index');
            }

            $queryBuilder = $usertypeRepository->findUserType($request->query->get('search'));
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('user_type/index.html.twig', [
                'usertypes' => $data,
                'form' => $form->createView(),
                'edit' => $id,
            ]);
        }
        $this->denyAccessUnlessGranted('user_type_create');
        $usertype = new UserType();
        $form = $this->createForm(UserTypeType::class, $usertype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usertype);
            $entityManager->flush();
            $origional = $log->changeObjectToArray($usertype);
            $message = $log->snew($origional, "", "create", $this->getUser(), "userType");
            return $this->redirectToRoute('usertype_index');
        }

        $queryBuilder = $usertypeRepository->findUserType($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('user_type/index.html.twig', [
            'usertypes' => $data,
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    /**
     * @Route("/{id}", name="usertype_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserType $usertype, LogService $log): Response
    {
        $this->denyAccessUnlessGranted('user_type_delete');
        if ($this->isCsrfTokenValid('delete' . $usertype->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $origional = $log->changeObjectToArray($usertype);
                $entityManager->remove($usertype);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), "userType");
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('usertype_index');
    }
}
