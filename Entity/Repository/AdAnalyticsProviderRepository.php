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

    public function inset()
    {
//        INSERT INTO `ad_analytics_provider`( `ad_id`, `created`, `updated`, `visits`) VALUES (1, now(), now(), 2)'
        $query = $this->getEntityManager()
            ->createQuery('INSERT INTO LSoftAdBundle:AdAnalyticsProvider ad ()

							WHERE nt.link IN (:urls)')
            ->setParameter('urls', $urls);
        $query->execute();
    }
}