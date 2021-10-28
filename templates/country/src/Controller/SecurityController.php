<?php

namespace App\Controller;

use App\Entity\SystemSetting;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
