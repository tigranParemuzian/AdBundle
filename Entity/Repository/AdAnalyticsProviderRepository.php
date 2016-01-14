<?php

namespace LSoft\AdBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AdAnalyticsProviderRepository extends EntityRepository
{
    /**
     * This repository find ads data by ad id
     *
     * @param $id
     * @return array
     */
    public function findByAdId($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT ap FROM LSoftAdBundle:AdAnalyticsProvider ap
                           LEFT JOIN ap.ad ads
                           WHERE ads.id = :id
                           ORDER BY ap.id ASC
                  ')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getOneOrNullResult()
            ;

    }
}