<?php

// src/OC/PlatformBundle/DataFixtures/ORM/LoadAdvert.php

namespace OC\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;

class LoadAdvert extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager){

        $listAdverts = array();

        for($i = 1 ; $i < 31 ; $i++){
            $randNumber = rand(5,365);
            $listAdverts[] = array(
                "title" => "New Title Number $i",
                "author" => "Author $i",
                "content" => "$i Advert Content",
                "date" => new \DateTime("-$randNumber days")
            );
        }
        foreach($listAdverts as $advertData){
            $advert = new Advert();
            $advert->setTitle($advertData['title']);
            $advert->setAuthor($advertData['author']);
            $advert->setContent($advertData['content']);
            $advert->setDate($advertData['date']);

            $manager->persist($advert);
        }

        $manager->flush();
    }

    public function getOrder(){
        return 3;
    }

}