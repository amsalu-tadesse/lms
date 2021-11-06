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

/**
 * @Route("/usertype")
 */
class UserTypeController extends AbstractController
{
    /**
     * @Route("/", name="usertype_index", methods={"GET","POST"})
     */
    public function index(UserTypeRepository $usertypeRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $usertype = $usertypeRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(UserTypeType::class, $usertype);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

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
        $usertype = new UserType();
        $form = $this->createForm(UserTypeType::class, $usertype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usertype);
            $entityManager->flush();

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
    public function delete(Request $request, UserType $usertype): Response
    {
        if ($this->isCsrfTokenValid('delete' . $usertype->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try
            {
                $entityManager->remove($usertype);
                $entityManager->flush();
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }

        }

        return $this->redirectToRoute('usertype_index');
    }
}
