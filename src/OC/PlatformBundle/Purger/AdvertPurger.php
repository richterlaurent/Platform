<?php

// src/OC/PlatformBundle/Purger/AdvertPurger.php

namespace OC\PlatformBundle\Purger;

use Doctrine\ORM\EntityManager;

class AdvertPurger
{

    private $em;

    /**
     * AdvertPurger constructor.
     *
     * EntityManager as parameter (services.yml) to access the repository
     * and persist/flush/remove.. methods
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     *
     * Deletes every advert that has no application and that is older than $days
     * Returns the number of advert entities affected
     *
     * @param $days
     * @return int
     */
    public function purge($days)
    {

        $date = new \DateTime("-$days days");

        $listAdverts = $this->em->getRepository('OCPlatformBundle:Advert')->getAdvertsBefore($date);

        // gets the total a adverts finally selected
        $count = count($listAdverts);

        // removes everything before being able to delete the advert entity (foreign keys)
        foreach($listAdverts as $advert){

            $advertSkills = $this->em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert' => $advert));

            foreach($advertSkills as $advertSkill){
                $this->em->remove($advertSkill);
            }

            $this->em->remove($advert);
        }

        $this->em->flush();

        return $count;
    }



}