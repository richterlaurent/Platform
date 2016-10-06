<?php

// src/OC/PlatformBundle/DataFixtures/ORM/LoadApplication.php

namespace OC\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Advert;

class LoadApplication extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager){

        $listApplications = array();

        for($i = 1 ; $i < 31 ; $i++){
            $listApplications[] = array(
                "author" => "Application Author $i",
                "content" => "Application Content $i"
            );
        }

        $listAdverts = $manager->getRepository('OCPlatformBundle:Advert')->findAll();
        $totalCount = count($listAdverts);

        foreach($listApplications as $appData){
            $application = new Application();
            $application->setAuthor($appData['author']);
            $application->setContent($appData['content']);
            $application->setAdvert($listAdverts[rand(0,$totalCount-1)]);
            $manager->persist($application);
        }

        $manager->flush();

    }


    public function getOrder(){
        return 4;
    }

}