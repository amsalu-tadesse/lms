<?php

namespace App\Twig;
 
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Form\FormView;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Andegna\DateTime as AD;
use App\Entity\Help;
 
class AppExtension extends AbstractExtension
{

    private $entityManager;
    private $urlGeneratorInterface;
    private $templating;

    public function __construct(EntityManagerInterface $em/*, UrlGeneratorInterface $urlGeneratorInterface, \Twig\Environment $templating*/)
    {

        $this->entityManager = $em;
  //$this->urlGeneratorInterface = $urlGeneratorInterface;
       // $this->templating = $templating;
    }


  

    public function getFunctions(): array
    {
        return [
      
            new TwigFunction('get_admin_helps', [$this, 'getAdminHelps']),
            new TwigFunction('get_stud_helps', [$this, 'getStudHelps']),
             
        ];
    }
    

   
    public function getAdminHelps()
    {
        $helps = $this->entityManager->getRepository(Help::class)->findAllAdminHelps();
        return $helps;
    }
    public function getStudHelps()
    {
        $helps = $this->entityManager->getRepository(Help::class)->findAllStudHelps();
        return $helps;
    }

 
   
 
}