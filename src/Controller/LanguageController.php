<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// use Symfony\Component\HttpFoundation\RequestEvent;

class LanguageController extends AbstractController
{

    /**
     * @Route("/language", name="language", methods={"GET","POST"})
     */

    public function index(Request $request)
    {

        $request->getSession()->set('_locale', $request->query->get("lang"));
        $next = $request->query->get("curr");
        //$route  = $request->get('_route');
        return $this->redirect($next);
         
    }

}
