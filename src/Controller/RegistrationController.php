<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Entity\StudentCourse;
use App\Entity\InstructorCourse;
use App\Entity\UserType;
use App\Entity\AcademicLevel;
use App\Repository\VerificationRepository;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Entity\Verification;
use App\Security\UserAuthenticator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;
use DateTime;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $emailVerifier, MailerInterface $mailer)
    {
        $this->emailVerifier = $emailVerifier;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, MailerInterface $mailer, MailerService $mservice): Response
    {

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $response = new Response();
            $form_data = array();
            $form_data['username'] = $form['username']->getData();
            $form_data['firstName'] = $form['firstName']->getData();
            $form_data['middleName'] = $form['middleName']->getData();
            $form_data['lastName'] = $form['lastName']->getData();
            $form_data['email'] = $form['email']->getData();
            $form_data['academicLevel'] = $form['academicLevel']->getData()->getId();
            
            //write form data to cookie
            $cookie = new Cookie('form_data', json_encode($form_data) ,time()*60*60);
            $response->headers->setCookie($cookie);
            $response->sendHeaders();

            $ver = new Verification();
            $ver->setEmail($form_data['email']);
            $ver->setVerificationCode(rand(23412,99999));
            $date = date('Y-m-d H:i', time());
            $date = new \DateTime('@'.strtotime("$date + 3 hours"));
            $ver->setVerificationExpiry($date);
            $em->persist($ver);
            $em->flush();

            $message = "this is text";
            $sent =  $mservice->sendEmail($mailer, $message, $form_data['email'], "text");

            return $this->render('registration/confirmation_email.html.twig', [
                'email' => $form_data['email'],
                "error" => ""
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     *  @Route("/verification", name="app_register_main")
     * 
     */
    public function registere(Request $request, VerificationRepository $ver_repo, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        $response = new Response();
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form_data = $request->cookies->get("form_data");
        $form_data = json_decode($form_data, true);
        
        $ver_validation = $ver_repo->findOneByEmail($form_data['email']);
        if(sizeof($ver_validation)>0 && sizeof($form_data)>0)
        {
            if($ver_validation[0]['verificationCode'] == $request->request->get("ver_code"))
            {
                $now = new \DateTime();
                if($now>$ver_validation[0]['verificationExpiry'])
                {
                    return $this->render('registration/confirmation_email.html.twig', [
                        'email' => $form_data['email'],
                        "error" => "verification code expired"
                    ]);
                }
                else{
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            "123456"
                        )
                    );
                    $user->setRoles(['ROLE_STUDENT']);
                    $user->setIsActive(true);
                    $user->setUserType($em->getRepository(UserType::class)->find(4));
                    $user->setFirstName($form_data['firstName']);
                    $user->setMiddleName($form_data['middleName']);
                    $user->setLastName($form_data['lastName']);
                    $user->setUsername($form_data['username']);
                    $user->setEmail($form_data['email']);

                    $em->persist($user);
                    $em->flush();

                    $conn = $this->getDoctrine()->getManager()->getConnection();
                    $sql = "delete from verification where email = :email";
                
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array('email' => $form_data['email']));

                    $cookie = new Cookie('form_data', "",time());
                    $response->headers->setCookie($cookie);
                    $response->sendHeaders();
                    
                    $student = new Student();
                    $student->setAcademicLevel($em->getRepository(AcademicLevel::class)->find($form_data['academicLevel']));
                    $student->setUser($user);
                    $em->persist($student);
                    $em->flush();

                    $selected_courses = $request->cookies->get("selected_courses");
                    $selected_courses = json_decode($selected_courses, true);
                    if($selected_courses != null){
                        $selected_courses_size = sizeof($selected_courses);

                        foreach($selected_courses as $sel_cor)
                        {
                            $st_course = new StudentCourse();
                            $st_course->setStudent($student);
                            $st_course->setInstructorCourse($entityManager->getRepository(InstructorCourse::class)->find($sel_cor));
                            $st_course->setStatus(0);
                            $st_course->setActive(0);
                            $st_course->setCreatedAt(new DateTime());
                            $em->persist($st_course);
                            $em->flush();
                        }

                        $cookie = new Cookie('selected_courses', "",time());
                        $response->headers->setCookie($cookie);
                        $response->sendHeaders();
                    }
                    return $this->redirectToRoute("app_login");
                }
            }
            else{
                return $this->render('registration/confirmation_email.html.twig', [
                    'email' => $form_data['email'],
                    "error" => "Wrong verification code"
                ]);
            }
        }

        return $this->redirectToRoute("app_register");
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
