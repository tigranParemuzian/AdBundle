<?php

/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 12/29/15
 * Time: 7:42 PM
 */

namespace LSoft\AdBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AdRepository extends EntityRepository
{
    public function findByAdsManager($domain, $zone)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM LSoftAdBundle:Ad a
                          JOIN LSoftAdBundle:AdsProvider ap WITH ap.ad = a
                          JOIN ap.domain d
                          WHERE d.name = :domain  and ap.zone = :zone')
            ->setParameters(array('domain'=>$domain, 'zone'=>$zone))
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    public function findParentById($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT ap, ads, d FROM LSoftAdBundle:AdsProvider ap
                            LEFT JOIN ap.ad ads
                            JOIN ap.domain d
                          WHERE ads.id = :id
                  ')
            ->setParameter('id', $id)
            ->getResult()
            ;

    }

    public function findParentByDomain($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT ap, ads, d FROM LSoftAdBundle:AdsProvider ap
                            LEFT JOIN ap.ad ads
                            JOIN ap.domain d
                          WHERE d.id = :id
                  ')
            ->setParameter('id', $id)
            ->getResult()
            ;

    }

}