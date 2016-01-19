<?php

namespace LSoft\AdBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class AdRepository extends EntityRepository
{
    /**
     * This repository find ads by domain and zone
     *
     * @param $domain
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByAdsManager($domain, $kay = null, $livetime = null)
    {
        $em = $this->getEntityManager();

        $dql= 'SELECT a as ad, ap.zone FROM LSoftAdBundle:Ad a
                          JOIN LSoftAdBundle:AdsProvider ap WITH ap.ad = a
                          JOIN ap.domain d
                          WHERE d.name = :domain';

        $cacheDriver = $em->getConfiguration()->getResultCacheImpl();
        $cacheId = (string)$kay;

        if ($cacheDriver) {
            if ($cacheDriver->contains($cacheId)) {
                return $cacheDriver->fetch($cacheId);
            }
        }

        $query = $em->createQuery($dql)
            ->setParameters(array('domain'=>$domain))
        ;
        ;
        $query->useResultCache(true, $livetime, $cacheId);
        $visions = $query->getResult();
        if ($cacheDriver) {
            //
            // Caching the hydrated result will save about 80% of loading time.
            //
            $cacheDriver->save($cacheId, $visions, $livetime);
        }

        $em->clear();
        return $visions;

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
    }

}