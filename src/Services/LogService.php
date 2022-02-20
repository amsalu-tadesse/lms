<?php

namespace App\Services;

use Exception;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Log;
use DateTime;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class LogService
{

   public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
   {
       $this->entityManager = $entityManager;
       $this->serializer = $serializer;
   }

   public function snew($original, $modified, $action, $actor, $entity){
      $log = new Log();
   
      // $edited = $this->serializer->normalize($modified, null, [AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);

      // if($original){
      //    foreach($original as $key=>$value){
      //       $originalArray[$key] = $value;
      //    }
      // }

      $log->setAction($action);
      $log->setOriginal(json_encode($original));
      $log->setModified(json_encode($modified));
      $log->setCreatedAt(new DateTime());
      $log->setActor($actor);
      $log->setModifiedEntity($entity);
      $this->entityManager->persist($log);
      $this->entityManager->flush();
      $message = $log->getId() ? true: false;
      return $message;
  }

  public function changeObjectToArray($obj)
  {
      $result = array();
      $cols = $obj->getAllFields();
      $allowedDataTypes = array("integer","double","string","boolean", "NULL");
      
      foreach($cols as $key => $value)
      {
         if(in_array(gettype($value), $allowedDataTypes)){
            $var = "get".ucfirst($key);
            if(!method_exists(get_class($obj), $var))
            // if(!$obj->$var() && $obj->$var() != NULL)
            {
               $var = ucfirst($key);
            }
           
            // if($obj->$var() != NULL){
               try {
                  //code...
                  $result[$key] = $obj->$var();
               } catch (\Throwable $th) {
                  //throw $th;
               }
              
            // }
         }
      }
      return $result;
  }
}
