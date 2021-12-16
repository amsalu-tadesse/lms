<?php
namespace App\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Entity\Product;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class SetFromListener implements EventSubscriberInterface
{

    
    

    /*public function onMessage(MessageEvent $event)
    {
      
        $email = $event->getMessage();
        if (!$email instanceof Email) {
            return;
        }

        $email->from(new Address('dawit120@gmail.com', 'wubetemaharyyytadessedesta'));
    }*/

    public static function getSubscribedEvents()
    {
       
        return [
            Events::postPersist=> 'postPersist',
            Events::postRemove=> 'postRemove',
            Events::postUpdate=> 'postUpdate',
        ];
       /* return [
            onMessage::class => 'postPersist',
            // onMessage::class => [['onMessage', 20]],
        ];*/
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        dd(8888);
        $this->logActivity('persist', $args);
        
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Product) {
            return;
        }

        // ... get the entity information and log it somehow
    }
}
