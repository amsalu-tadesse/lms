<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\Student;
use App\Entity\SystemSetting;
use App\Entity\User;
use App\Form\Filter\UserFilterType;
use App\Form\UserType;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Mime\NamedAddress;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Services\LogService;

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
    public function index(StudentRepository $student_repo, Request $request, UserRepository $userRepository, PaginatorInterface $paginator, UserPasswordEncoderInterface $userPasswordEncoderInterface, MailerService $mservice, LogService $log): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // $this->denyAccessUnlessGranted('content_edit');
        $pageSize = 15;

        $user = new User();
        $searchForm = $this->createForm(UserFilterType::class, $user);
        $searchForm->handleRequest($request);

        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $user = $userRepository->findOneBy(['id' => $id]);
            $origional = $log->changeObjectToArray($user);
            $form = $this->createForm(userType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $modified = $log->changeObjectToArray($user);
                $message = $log->snew($origional, $modified, "update", $this->getUser(), "user");

                return $this->redirectToRoute('user_index');
            }

            $queryBuilder = $userRepository->filterUser($request->query->get('firstName'), $request->query->get('middleName'), $request->query->get('lastName'), $request->query->get('sex'), $request->query->get('userType'));
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
            $origional = $log->changeObjectToArray($user);

            $form = $this->createForm(userType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $role = $this->getCustomRoleNames($form->getData()->getUserType()->getId());
                $user->setRoles([$role]);
                $entityManager->persist($user);
                $entityManager->flush();
                $modified = $log->changeObjectToArray($user);
                $message = $log->snew($origional, $modified, "update", $this->getUser(), "user");
                return $this->redirectToRoute('user_index');
            }
        }

        $form = $this->createForm(userType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $password = $user->getPassword();
            if (!$password) {
                $password = $this->rand_string();
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
            // dd("l");
            $user->setCreatedAt(new DateTime());
            $entityManager->persist($user);
            $entityManager->flush();

            $origional = $log->changeObjectToArray($user);
            $message = $log->snew($origional, "", "create", $this->getUser(), "user");

            if ($user->getUserType()->getId() == 3) { //instructor
                $instructor = new Instructor();
                $instructor->setUser($user);
                $entityManager->persist($instructor);
                $origional = $log->changeObjectToArray($instructor);
                $message = $log->snew($origional, "", "create", $this->getUser(), "instructor");
            } elseif ($user->getUserType()->getId() == 4) { //student
                $student = new Student();
                $em = $this->getDoctrine()->getManager();
                
                $academicLevel = $form['academicLevel']->getData()->getId();
                if($academicLevel)
                {
                    if($academicLevel == 1)
                        $prefix = $em->getRepository(SystemSetting::class)->findOneBy(['code'=>'reg_id_prefix'])->getValue();
                    else if($academicLevel == 2)
                        $prefix = $em->getRepository(SystemSetting::class)->findOneBy(['code'=>'masters_id_prefix'])->getValue();
                    else if($academicLevel == 3)
                        $prefix = $em->getRepository(SystemSetting::class)->findOneBy(['code'=>'phd_id_prefix'])->getValue();
                    else if($academicLevel == 5)
                        $prefix = $em->getRepository(SystemSetting::class)->findOneBy(['code'=>'hd_id_prefix'])->getValue();
                    
                    $last_student = $student_repo->findBy(array(),array('id'=>'DESC'),1,0);
                    $id_digit_length = $em->getRepository(SystemSetting::class)->findOneBy(['code'=>'num_of_digits_student_id'])->getValue();
                    $year = date("y", time());

                    if($last_student){
                        $last_student_id_array = explode("-",$last_student[0]->getStudentId());
                        $num = $last_student_id_array[1] + 1;
                        $numlength = strlen((string)$num);
                        if($year == $last_student_id_array[2]){
                            if($numlength < $id_digit_length)
                            {
                                $zeros_left = $id_digit_length - $numlength;
                                $i = 0;
                                while($i<$zeros_left)
                                {
                                    $num = "0".$num;
                                    $i++;
                                }
                                $new_student_id = $prefix."-".$num."-".$year;
                            }
                        }
                        else{
                            $new_student_id = $prefix."-";
                            $i = 0;
                                while($i<($id_digit_length-1))
                                {
                                    $new_student_id .= "0";
                                    $i++;
                                }
                            $new_student_id .= "1-".$year;
                        }
                    }
                    else{
                        $i = 0;
                        $new_student_id = $prefix."-";
                        while($i<($id_digit_length-1))
                        {
                            $new_student_id .= "0";
                            $i++;
                        }
                        $new_student_id .= "1-".$year;
                    }
                }
                $student->setProfileUpdated(0);
                $student->setStudentId($new_student_id);
                $student->setUser($user);
                $student->setAcademicLevel($form['academicLevel']->getData());
                $entityManager->persist($student);

                $origional = $log->changeObjectToArray($student);
                $message = $log->snew($origional, "", "create", $this->getUser(), "student");
            }
            $entityManager->flush();

            $message = "<p style='font-size: 15px;'>Dear ".$user->getFirstName()." ".$user->getMiddleName()." You have successfully registered for ECA ".
            "Learning management system. Please login with the following credentials".
            " and change your password <br>username=<strong>".$user->getUsername()."</strong><br> password=<strong>".$password."</strong></p>";
            
            $sent =  $mservice->sendEmail($this->mailer, $message, $user->getEmail(), "account confirmation");

            return $this->redirectToRoute('user_index');
        }

        $queryBuilder = $userRepository->filterUser($request->query->get('firstName'), $request->query->get('middleName'), $request->query->get('lastName'), $request->query->get('sex'), $request->query->get('userType'));
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

    public function new(Request $request, UserPasswordEncoderInterface $userPasswordEncoderInterface, LogService $log): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setPassword($userPasswordEncoderInterface->encodePassword($user,$user->getPassword()));
            $password = $user->getPassword();
            if (!$password) {
                $password = $this->rand_string();
            }

            $user->setPassword($userPasswordEncoderInterface->encodePassword($user, $password));
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $origional = $log->changeObjectToArray($user);
            $message = $log->snew($origional, "", "create", $this->getUser(), "user");

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
    public function edit(Request $request, User $user, LogService $log): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $origional = $log->changeObjectToArray($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($user);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "user");
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
    public function delete(Request $request, User $user, LogService $log): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            try
            {
                $origional = $log->changeObjectToArray($user);
                $entityManager->remove($user);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), "user");
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('user_index');
    }

    function rand_string() {
		$small_letter = $this->shuffle_string("abcdefghijklmnopqrstuvwxyz",3);
		$capital_letter = $this->shuffle_string("ABCDEFGHJKLMNOPQRSTUVWXYZ",1);
		$number = $this->shuffle_string("1234567890",3);
		$symbol = $this->shuffle_string("@&",1);
		$password = $capital_letter.$small_letter.$symbol.$number;
		return $password;
    }

    function shuffle_string($str, $length)
    {
        return substr(str_shuffle($str),0,$length);
    }
}
