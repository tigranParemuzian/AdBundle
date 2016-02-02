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
    public function findByAdsManager($domain, $kay = null, $livetime = null, $update = null)
    {
        $em = $this->getEntityManager();

        $dql= 'SELECT a as ad, ap.zone FROM LSoftAdBundle:Ad a
                          JOIN LSoftAdBundle:AdsProvider ap WITH ap.ad = a
                          JOIN ap.domain d
                          WHERE d.name = :domain';
        // get doctrine cache
        $cacheDriver = $em->getConfiguration()->getResultCacheImpl();
        // create kay for cache id
        $cacheId = (string)$kay;

        // if need update
        if($update != null)
        {
            // check cache enable
            if ($cacheDriver) {
                // check isset data in cache
                if ($cacheDriver->contains($cacheId)) {
                    // delete data from cache
                    $cacheDriver->delete($cacheId);
                }
            }
        }
        else{
            // check cache enable
            if ($cacheDriver) {
                // check isset data in cache
                if ($cacheDriver->contains($cacheId)) {
                    // return data fro cache
                    return $cacheDriver->fetch($cacheId);
                }
            }
        }

       // create query by dql
        $query = $em->createQuery($dql)
            ->setParameters(array('domain'=>$domain)) ;
        // enable result cache
        $query->useResultCache(true, $livetime, $cacheId);
        // get result
        $visions = $query->getResult();
        if ($cacheDriver && $visions != null) {
            //
            // Caching the hydrated result will save about 80% of loading time.
            //
            $cacheDriver->save($cacheId, $visions, $livetime);
        }

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
            ->createQuery('SELECT d.name FROM LSoftAdBundle:AdsProvider ap
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
     * This repository find domain names by ads provider id
     *
     * @param $id
     * @return array
     */
    public function findDomainsByProvider($id)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT d.name FROM LSoftAdBundle:AdsProvider ap
                            JOIN ap.domain d
                          WHERE ap.id = :id
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

    /**
     * This repository find all ads order by dimensionIndex
     *
     * @return array
     */
    public function findAllForAnalytics()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT ad.id, ad.name, ad.dimensionIndex
                            FROM LSoftAdBundle:Ad ad
                            WHERE ad.id IS NOT NULL
                            ORDER BY ad.dimensionIndex ASC ')
            ->getArrayResult()
            ;
    }

}