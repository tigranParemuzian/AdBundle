<?php

namespace LSoft\AdBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AdRepository extends EntityRepository
{
    /**
     * This repository find ads by domain and zone
     *
     * @param $domain
     * @param $zone
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
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

    /**
     * This repository find ads data by ad id
     *
     * @param $id
     * @return array
     */
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

    /**
     * This repository find ads data by domain id
     *
     * @param $id
     * @return array
     */
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

    /**
     * This repository find ads data for analytics
     *
     * @return array
     */
    public function findAnalytics($ads = null, $from=null, $to=null)
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('ad')
            ->from('LSoftAdBundle:Ad', 'ad')
            ->where('ad.id IS NOT NULL')
        ;

        if($ads != null)
        {
            $query->andWhere('ad.id IN :ads_ids')
            ->setParameter('ads_ids', $ads);
        }
        return $query->getQuery()->getResult();
//        return $this->getEntityManager()
//            ->createQuery('SELECT ad, adan  FROM LSoftAdBundle:Ad ad
//                           LEFT JOIN ad.adAnalytics adan
//                           WHERE ad.id IS NOT NULL
//                           GROUP BY ad.id
//                  ')
//            ->getResult()
//            ;

    }

}