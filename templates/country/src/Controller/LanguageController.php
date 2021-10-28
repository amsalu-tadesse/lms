<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;

// use Symfony\Component\HttpFoundation\RequestEvent;

class LanguageController extends AbstractController
{
    
    /**
     * @Route("/language", name="language", methods={"GET","POST"})
     */

    public function index(Request $request) 
    {
      
        $request->getSession()->set('_locale',$request->query->get("lang"));
       // $request->getSession()->set('_locale','fr');
       
      //  $route  = $request->get('_route');


        return $this->redirectToRoute('home');
        // return $this->redirectToRoute("home");
    }

     
}