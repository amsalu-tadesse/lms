<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\Student;
use App\Entity\User;
use App\Form\Filter\UserFilterType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="user_index", methods={"GET","POST"})
     */
    public function index(Request $request, UserRepository $userRepository, PaginatorInterface $paginator, UserPasswordEncoderInterface $userPasswordEncoderInterface, MailerService $mservice): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $pageSize = 15;

        $user = new User();
        $searchForm = $this->createForm(userFilterType::class, $user);
        $searchForm->handleRequest($request);

        if ($request->request->get('edit')) {

            $id = $request->request->get('edit');
            $user = $userRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(userType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // $user->setRoles([$role]);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('user_index');
            }

            $queryBuilder = $userRepository->filterUser($request->query->get('firstName'), $request->query->get('middleName'), $request->query->get('lastName'), $request->query->get('userName'), $request->query->get('userType'));
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                $pageSize
            );
            return $this->render('user/index.html.twig', [
                'users' => $data,
                'form' => $form->createView(),
                'searchForm' => $searchForm->createView(),
                'edit' => $id,
            ]);
        }

        if ($request->request->get("userid")) {

            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->find($request->request->get("userid"));
            $form = $this->createForm(userType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $role = $this->getCustomRoleNames($form->getData()->getUserType()->getId());
                $user->setRoles([$role]);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('user_index');
            }

        }

        $form = $this->createForm(userType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $password = $user->getPassword();
            if (!$password) {
                $password = rand(223232, 998999);
            }

            $user->setPassword($userPasswordEncoderInterface->encodePassword($user, $password));

           
            $role = $this->getCustomRoleNames($user->getUserType()->getId());

            $user->setRoles([$role]);



            $username1 = $user->getFirstName() . '.' . $user->getMiddleName();
            $username = $username1;
            $found = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
            $counter = 0;
            while ($found) {
                $counter++;
                $username_new = $username1 . $counter;
                $username = $username_new;
                $found = $entityManager->getRepository(User::class)->findOneBy(['username' => $username_new]);

            }
         
            

            $email = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
            if ($email) {
                $this->addFlash('danger', 'Email is reserved');
                return $this->redirectToRoute('user_index');
            }
            $user->setUsername($username);
            $user->setIsActive(true);
            $entityManager->persist($user);
            $entityManager->flush();

            if ($user->getUserType()->getId() == 3) //instructor
            {
                $instructor = new Instructor();
                $instructor->setUser($user);
                $entityManager->persist($instructor);

            } else if ($user->getUserType()->getId() == 4) //student
            {
                $student = new Student();
                $student->setUser($user);
                $entityManager->persist($student);

            }
            $entityManager->flush();

            $message = "<p style='font-size: 15px;'>Dear ".$user->getFirstName()." ".$user->getMiddleName()." You have successfully registered for ECA ".
            "Learning management system. Please login with the following credentials".
            " and change your password <br>username=<strong>".$user->getUsername()."</strong><br> password=<strong>".$password."</strong></p>";
            
            $sent =  $mservice->sendEmail($this->mailer, $message, $user->getEmail(), "account confirmation");

            return $this->redirectToRoute('user_index');
        }

        $queryBuilder = $userRepository->filterUser($request->query->get('firstName'), $request->query->get('middleName'), $request->query->get('lastName'), $request->query->get('userName'), $request->query->get('userType'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $pageSize
        );

        return $this->render('user/index.html.twig', [
            'users' => $data,
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'edit' => false,
        ]);
    }

    public function getCustomRoleNames($id)
    {

        $role = "";

        switch ($id) {
            case '1':
                # code...
                $role = "ROLE_ADMIN";

                break;
            case '2':
                # code...
                $role = "ROLE_DIRECTOR";
                break;
            case '3':
                # code...
                $role = "ROLE_INSTRUCTOR";
                break;
            case '4':
                # code...
                $role = "ROLE_STUDENT";
                break;

            default:
                # code...
                $role = "ROLE_USER";
                break;
        }
        return $role;
    }
    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */

    function new (Request $request, UserPasswordEncoderInterface $userPasswordEncoderInterface): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setPassword($userPasswordEncoderInterface->encodePassword($user,$user->getPassword()));
            $password = $user->getPassword();
            if (!$password) {
                $password = rand(223232, 998999);
            }

            $user->setPassword($userPasswordEncoderInterface->encodePassword($user, $password));
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        // return new Response("done");
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
           

            try
            {
                $entityManager->remove($user);
                $entityManager->flush();
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }


        }

        return $this->redirectToRoute('user_index');
    }

}
