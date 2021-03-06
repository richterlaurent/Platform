<?php

namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{
    public function getAdverts(){
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.image','i')->addSelect('i')
            ->leftJoin('a.categories','c')->addSelect('c')
            ->leftJoin('a.advertSkills','advs')->addSelect('advs')
            ->orderBy('a.date', 'desc');
        return $qb;
    }

    // return list of every advert (with advert skills)
    // that has no application at all and with updatedAt or creation date if no update
    // before the date in parameter
    public function getAdvertsBefore($date){
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.advertSkills','advs')->addSelect('advs')
            ->where('a.updatedAt <= :date')
            ->orWhere('a.updatedAt IS NULL AND a.date <= :date')
            ->andWhere('a.applications IS EMPTY')
            ->setParameter('date',$date);

        return $qb->getQuery()->getResult();
    }


    // unused function but interesting example of expr() use =>
    public function getAdvertWithCategories(array $categoryNames){
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.categories', 'c')->addSelect('c');
        $qb->where($qb->expr()->in('c.name', $categoryNames));
        return $qb->getQuery()->getResult();
    }

}
