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
        // return a queryBuilder that gets every advert with no applications
        $qb = $this->em->getRepository('OCPlatformBundle:Advert')->getAdvertsWithoutApplicationQueryBuilder();

        // adds a filter regarding the number of days
        $qb->andWhere('a.date < :end')
            ->setParameter('end', new \DateTime("-$days days"));

        $listAdverts = $qb->getQuery()->getResult();

        // gets the total a adverts finally selected
        $count = count($listAdverts);

        // removes everything before being able to delete the advert entity (foreign keys)
        foreach($listAdverts as $advert){
            foreach($advert->getAdvertSkills() as $advertSkill){
                $advert->removeAdvertSkill($advertSkill);
                $this->em->remove($advertSkill);
            }
            foreach($advert->getCategories() as $category){
                $advert->removeCategory($category);
            }
            $this->em->remove($advert);
        }

        $this->em->flush();

        return $count;
    }



}