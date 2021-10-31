<?php

namespace App\Controller;

use App\Entity\SystemSetting;
use App\Entity\User;
use App\Entity\Verification;
use App\Form\ForgotPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use App\Repository\VerificationRepository;
use App\Services\MailerService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // $org_name = $this->getDoctrine()->getManager()->getRepository(SystemSetting::class)->findBy(['code'=>'org_name_am'])[0]->getValue();

        return $this->render('security/login.html.twig', ['org_name'=>'org_name', 'last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/email", name="email_verification")
     */
    public function emailVerification(Request $request, MailerInterface $mailer, MailerService $mservice): Response
    {
        //ForgotPasswordType
        if($request->isMethod('post')){

            $em = $this->getDoctrine()->getManager();
            $email = $request->request->get('email');
            $ver = new Verification();
            $ver->setEmail($email);
            $code = rand(23412,99999);
            $ver->setVerificationCode($code);
            $date = date('Y-m-d H:i', time());
            $date = new \DateTime('@'.strtotime("$date + 3 hours"));
            $ver->setVerificationExpiry($date);
            $em->persist($ver);
            $em->flush();

            $message = "verification code is <b>$code</b> ";
            $sent =  $mservice->sendEmail($mailer, $message, $email, "account");

            $error = '';

            return $this->render('security/verification-code.html.twig', 
                    [
                        'error' => $error,
                        'email' => $email
                    ]);
        }
        
        $error = "";
        return $this->render('security/email-verification.html.twig', ['error' => $error]);
    }

    /**
     * @Route("/email/verification", name="email_verification_validation", methods={"GET","POST"})
     */
    public function emailVer(VerificationRepository $ver_repo, Request $request): Response
    {
        $ver_validation = $ver_repo->findOneByEmail($request->request->get("email"));
        if(sizeof($ver_validation)>0)
        {
            if($ver_validation[0]['verificationCode'] == $request->request->get("code"))
            {
                $now = new \DateTime();
                if($now>$ver_validation[0]['verificationExpiry'])
                {
                    return $this->render('security/verification-code.html.twig', [
                        'email' => $request->request->get('email'),
                        "error" => "verification code expired"
                    ]);
                }
                else{
                    $request->getSession()->set('password_change_email', $request->request->get("email"));
                    return $this->redirectToRoute("password_change");
                }
            }
            else{
                return $this->render('security/verification-code.html.twig', [
                    'email' => $request->request->get('ddataaa'),
                    "error" => "Wrong verification code"
                ]);
            }
        }
        else{
            return $this->render('security/email-verification.html.twig', ['error' => "Email not found"]);
        }
        
    }

    /**
     * @Route("/password", name="password_change", methods={"GET","POST"})
     */
    public function passwordChangeForm(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        $email = $request->getSession()->get('password_change_email');
        if($email == null)
        {
            return $this->redirectToRoute('app_login');
        }        
        if ($form->isSubmitted() && $form->isValid()) {
    
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneBy(array('email' => $email));
            $user->setPassword($passwordEncoder->encodePassword($user, $form['plainPassword']->getData()));
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/password_change.html.twig', [
            'form' => $form,
            'error' => ""
        ]);
    }

        /**
     * @Route("/password/change", name="change_password", methods={"GET","POST"})
     */
    public function passwordChange(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);

        $email = $request->getSession()->get('password_change_email');
        if($email == null)
        {
            return $this->redirectToRoute('app_login');
        }        
        if ($form->isSubmitted() && $form->isValid()) {
    
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(User::class)->findOneBy(array('email' => $email));
            $user->setPassword($passwordEncoder->encodePassword($user, $form['plainPassword']->getData()));
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/password_change.html.twig', [
            'form' => $form,
            'error' => ""
        ]);
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
/*
    public function isAllowed(User $user=null, $action)
    {
        if ($user==null) {
            throw new AccessDeniedException();
        }
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }
        if ($user->getUserGroup()) {
            foreach ($user->getUserGroup()[0]->getPermission() as $key => $perm) {
                if ($action==$perm->getName()) {
                    return true;
                }
            }
            throw new AccessDeniedException();
        } else {
            throw new AccessDeniedException();
        }
        // Do what you need, $this->entityManager holds a reference to your entity manager
    }
    public function isAllowedTwig(User $user, $action)
    {
        if (!$user) {
            return false;
        }
        if ($user->hasRole('ROLE_ADMIN')) {
            return true;
        }
        if (!$user->getUserGroup()) {
            return false;
        }
        foreach ($user->getUserGroup()->getPermission() as $key => $perm) {
            if ($action==$perm->getName()) {
                return true;
            }
        }

        return false;
    
        //
    }*/
}
