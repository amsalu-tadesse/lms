<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Entity\StudentCourse;
use App\Entity\InstructorCourse;
use App\Entity\UserType;
use App\Entity\SystemSetting;
use App\Entity\AcademicLevel;
use App\Repository\StudentRepository;
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

            // if(empt)

            $user_valid = $em->getRepository(User::class)->findBy(array('email'=>$form['email']->getData()));
            if ($user_valid == null) {
                $response = new Response();
                $form_data = array();
                // $form_data['username'] = $form['username']->getData();
                $form_data['firstName'] = $form['firstName']->getData();
                $form_data['middleName'] = $form['middleName']->getData();
                $form_data['lastName'] = $form['lastName']->getData();
                $form_data['email'] = $form['email']->getData();
                $form_data['academicLevel'] = $form['academicLevel']->getData()->getId();

                //write form data to cookie
                $cookie = new Cookie('form_data', json_encode($form_data), time()*60*60);
                $response->headers->setCookie($cookie);
                $response->sendHeaders();

                $ver = new Verification();
                $ver->setEmail($form_data['email']);
                $code = rand(23412, 99999);
                $ver->setVerificationCode($code);
                $date = date('Y-m-d H:i', time());
                $date = new \DateTime('@'.strtotime("$date + 3 hours"));
                $ver->setVerificationExpiry($date);
                $em->persist($ver);
                $em->flush();

                $message = "verification code is <b>$code</b> ";
                $sent =  $mservice->sendEmail($mailer, $message, $form_data['email'], "account verification");

                return $this->render('registration/confirmation_email.html.twig', [
                    'email' => $form_data['email'],
                    "error" => ""
                ]);
            } else {
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'message' => 'This email is already registered. please use another email'
                ]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'message' => ''
        ]);
    }

    /**
     *  @Route("/verification", name="app_register_main")
     *
     */
    public function registere(Request $request, VerificationRepository $ver_repo, StudentRepository $student_repo, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator, MailerService $mservice): Response
    {
        $form_data = $request->cookies->get("form_data");
        $form_data = json_decode($form_data, true);

        if (empty($form_data)) {
            return $this->redirectToRoute("app_register");
        }

        $response = new Response();
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $ver_validation = $ver_repo->findOneByEmail($form_data['email']);
        if (sizeof($ver_validation)>0 && sizeof($form_data)>0) {
            if ($ver_validation[0]['verificationCode'] == $request->request->get("ver_code")) {
                $now = new \DateTime();
                if ($now>$ver_validation[0]['verificationExpiry']) {
                    return $this->render('registration/confirmation_email.html.twig', [
                        'email' => $form_data['email'],
                        "error" => "verification code expired"
                    ]);
                }
                else{
                    $pass = $this->rand_string();
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            "$pass"
                        )
                    );
                    $user->setRoles(['ROLE_STUDENT']);
                    $user->setIsActive(true);
                    $user->setUserType($em->getRepository(UserType::class)->find(4));
                    $user->setFirstName($form_data['firstName']);
                    $user->setMiddleName($form_data['middleName']);
                    $user->setLastName($form_data['lastName']);
                    $entityManager = $this->getDoctrine()->getManager();

                    $username1 = $form_data['firstName'] . '.' . $form_data['middleName'];
                    $username = $username1;
                    $found = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
                    $counter = 0;
                    while ($found) {
                        $counter++;
                        $username_new = $username1 . $counter;
                        $username = $username_new;
                        $found = $entityManager->getRepository(User::class)->findOneBy(['username' => $username_new]);
                    }
                    $user->setUsername($username);
                    $user->setIsVerified(1);
                    $user->setCreatedAt(new DateTime());
                    $user->setEmail($form_data['email']);

                    $em->persist($user);
                    $em->flush();

                    $conn = $this->getDoctrine()->getManager()->getConnection();
                    $sql = "delete from verification where email = :email";

                    $stmt = $conn->prepare($sql);
                    $stmt->execute(array('email' => $form_data['email']));

                    $student = new Student();
                    $student->setAcademicLevel($em->getRepository(AcademicLevel::class)->find($form_data['academicLevel']));
                    $student->setUser($user);

                    //student_id
                    $academicLevel = $form_data['academicLevel'];
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

                    //clear cookie
                    $cookie = new Cookie('form_data', "",time());
                    $response->headers->setCookie($cookie);
                    $response->sendHeaders();
                    

                    $em->persist($student);
                    $em->flush();

                    $selected_courses = $request->cookies->get("selected_courses");
                    $selected_courses = json_decode($selected_courses, true);
                    if ($selected_courses != null) {
                        $selected_courses_size = sizeof($selected_courses);

                        foreach ($selected_courses as $sel_cor) {
                            $st_course = new StudentCourse();
                            $st_course->setStudent($student);
                            $st_course->setInstructorCourse($em->getRepository(InstructorCourse::class)->find($sel_cor));
                            $st_course->setStatus(0);
                            $st_course->setActive(0);
                            $st_course->setTeacherNotification(0);
                            $st_course->setDirectorNotification(0);
                            $st_course->setCreatedAt(new DateTime());
                            $em->persist($st_course);
                            $em->flush();
                        }

                        $cookie = new Cookie('selected_courses', "", time());
                        $response->headers->setCookie($cookie);
                        $response->sendHeaders();
                    }

                    $message = "<p style='font-size: 15px;'>Dear ".$user->getFirstName()." ".$user->getMiddleName()." You have successfully registered for ECA ".
                               "Learning management system. Please login with the following credentials".
                               " and change your password <br>username=<strong>".$user->getUsername()."</strong><br> password=<strong>$pass</strong></p>";
                    $sent =  $mservice->sendEmail($this->mailer, $message, $user->getEmail(), "account confirmation");

                    return $this->render('registration/notification.html.twig');
                }
            } else {
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
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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
