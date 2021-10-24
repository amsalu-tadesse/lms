<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Entity\StudentCourse;
use App\Entity\InstructorCourse;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
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
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    "123456"
                )
            );

            // handle the user registration form and persist the new user...
        
            $user->setRoles(['ROLE_STUDENT']);
            $user->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $student = new Student();
            $student->setAcademicLevel($form->get("academicLevel")->getData());
            $student->setUser($user);
            $entityManager->persist($student);
            $entityManager->flush();

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
                    $entityManager->persist($st_course);
                    $entityManager->flush();
                }

                $response = new Response();
                $cookie = new Cookie('selected_courses', "",time());
                $response->headers->setCookie($cookie);
                $response->sendHeaders();
            }


            // $signatureComponents = $this->emailVerifier->generateSignature(
            //     'app_verify_email',
            //     $user->getId(),
            //     $user->getEmail()
            // );
            // $email = new TemplatedEmail();
            // $email->from(new Address('dawit120@gmail.com', 'Amsalu Tadesse'));
            // $email->to($user->getEmail());
            // $email->subject("Please Confirm Your email");
            // $email->htmlTemplate('registration/confirmation_email.html.twig');
            // $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
         
            // $this->mailer->send($email);
            
            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'main' // firewall name in security.yaml
            // );

            return $this->redirectToRoute("app_login");
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
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
