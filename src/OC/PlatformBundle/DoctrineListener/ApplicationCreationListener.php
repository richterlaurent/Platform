<?php

// src/OC/PlatformBundle/DoctrineListener/ApplicationCreationListener.php

namespace OC\PlatformBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use OC\PlatformBundle\Email\ApplicationMailer;
use OC\PlatformBundle\Entity\Application;

class ApplicationCreationListener
{
    /**
     * @var \ApplicationMailer
     */
    private $applicationMailer;


    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Application)
        {
            return;
        }


        try {
            $this->applicationMailer->sendNewNotification($entity);
        }catch(\Swift_RfcComplianceException $e){
//            echo $e->getMessage();
            // à voir ce qu'il faut faire exactement ici pour avoir un templating correct en cas d'erreur.
        }
    }




}