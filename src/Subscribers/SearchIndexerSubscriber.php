<?php

// src/EventListener/SearchIndexerSubscriber.php
namespace App\Subscribers;

use App\Entity\Log;
use App\Services\MailerService;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
//use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class SearchIndexerSubscriber implements EventSubscriber
{
    private $objs;
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
            Events::preRemove,
            Events::preUpdate,
           
        ];
    }

  
    public function postPersist(LifecycleEventArgs $args)
    {
       
       
        $this->logActivity('persist', $args);
    }
 
    public function postUpdate(LifecycleEventArgs $args)
    {
      //  $entityChange = $args->getEntityChangeSet();
       // dd($entityChange);

        $this->logActivity('postupdate', $args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entityChange = $args->getEntityChangeSet();
        $this->obj['modified'] = $entityChange;
        //dd($entityChange);

        //$this->logActivity('preupdate', $args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        // $entityChange = $args->getEntityChangeSet();
        // dd($entityChange);
        $this->logActivity('remove', $args);
    }

    function delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
          return $string;
        }
      
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
      
        return $this->delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
      }
      function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    


    public function logActivity(string $action, LifecycleEventArgs $args)
    {
       // $logData = array();
        $entity = $args->getObject();
        
        // dd($entity);
       // $logData['entity'] = $entity;
        //$this->obj['newObject'] = $entity;
// dd($entity);
 


$original = json_encode((array)$entity);


// dd($original);
//$original = json_encode((array)$entity);
//$modified = json_encode($this->obj['modified']);
// $actor = $this->getUser();
 
//$myText = 'text here ABC -some text here- CED text here';
       // "text here ABC - replaced text - CED text here"
//$myText = preg_replace('/ABC(.+)CED/', 'ABC - replaced text - CED', $myText);
/*
$myText = preg_replace('/\u0000(.+)\u0000/', '', $original);
dd ($myText);
*/


// $original = preg_replace('/\u0000/', '', $original);
//  dd($original);
 
 /*
$pattern = '/'.$this->get_string_between("\u0000","\u0000",$original).'/i';
echo preg_replace($pattern, '', $original);*/


 





      

        /*if($action=="persist")
        {
        
        }
        else if($action=="postupdate")
        {
            
        }
        else if($action=="remove")
        {
            
        }
    
        if ($entity instanceof Product) {
            $entityManager = $args->getObjectManager();
         }*/
        return;
    }
}