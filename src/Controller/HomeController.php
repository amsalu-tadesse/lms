<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $date = date('Y-m-d H:i', time());
        $newtimestamp = strtotime("$date + 3 hours");
        dd($newtimestamp);
        echo date('Y-m-d H:i:s', $newtimestamp);
        dd("hello");
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->redirectToRoute("courses_list");
    }
}
