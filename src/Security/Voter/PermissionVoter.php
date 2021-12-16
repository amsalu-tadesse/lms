<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PermissionVoter extends Voter
{
    private $session;
    public function __construct(SessionInterface $sessionInterface)
    {
        $this->session=$sessionInterface;
    }
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html

        $permission=$this->session->get("PERMISSION");
        // dd($attribute);
        if (!$permission) {
            $permission=array();
        }
        if ($attribute == "pat_") {
            return true;
        }
        return in_array($attribute, $permission);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $permission=$this->session->get("PERMISSION");
        // dd($permission);
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'pat_':
                $matches  = preg_grep('/^pat_(\w+)/i', $permission);
              if ($matches) {
                  return true;
              } else {
                   return false;
               }

                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST_VIEW':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }
        /*if($user->getId()==1)
        return true;*/

        if (!$permission) {
            $permission=array();
        }

        return in_array($attribute, $permission);

        return false;
    }
}
