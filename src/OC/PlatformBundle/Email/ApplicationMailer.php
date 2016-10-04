<?php

// src/OC/PlatformBundle/Email/ApplicationMailer.php

namespace OC\PlatformBundle\Email;

use OC\PlatformBundle\Entity\Application;

class ApplicationMailer
{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application)
    {
        try{

            $message = new \Swift_Message(
                'Nouvelle candidature',
                'Vous avez reçu une nouvelle candidature.'
            );

            $message
                ->addTo($application->getAdvert()->getAuthor())
                ->addFrom('admin@votresite.com');

            $this->mailer->send($message);

        }catch(\Swift_RfcComplianceException $e){
            throw new \Swift_RfcComplianceException("Arrêt dans l'envoi de l'email : erreur dans l'adresse du destinataire");
        }

    }

}