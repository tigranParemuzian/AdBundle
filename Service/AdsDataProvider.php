<?php

namespace LSoft\AdBundle\Service;

use LSoft\AdBundle\Entity\Ad;
use LSoft\AdBundle\Entity\AdAnalyticsProvider;
use LSoft\AdBundle\Entity\AdsAnalytics;
use LSoft\AdBundle\Entity\AdsProvider;
use LSoft\AdBundle\Entity\Domain;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class AdsService
 * @package LSoft\AdBundle\Service
 */
class AdsDataProvider
{
    /**
     * @var Container
     */
    private $container;

    /**
     * get Symfony Dependency Injection Container
     *
     * @param Container $container
     */
    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }

    /**
     * This function get data
     *
     * @param $domain
     * @param $zone
     * @return mixed
     */
    public function checkData($domain, $zone)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getEntityManager();
        // get key
        $key = $this->cretePattern($domain, $zone);

        // check apc php apc is enable
        if(extension_loaded('apc') && ini_get('apc.enabled'))
        {
            // check data in apc by key
            $data = apc_fetch((string)$key);

            // set timeout for apc cache
            if(!$data)
            {
                // get ad manager
                $ad = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $zone);
                // get lifetime og apc
                $lifetime = $this->container->getParameter('l_soft_ad.lifetime');
                // add data in apc timeout 1 day
                apc_add((string)$key, $ad, $lifetime);
                // get data from apc
                $data = apc_fetch((string)$key);
            }
        }
        else
        {
            // if cache not exist get data
            $data = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $zone);
        }

        if($data != null)
        {
            $this->checkAnalytics($data->getId());
        }

        return $data;
    }

    /**
     * @param $object
     */
    public function updateApc($object)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getEntityManager();
        // get lifetime of apc caching from configs
        $lifetime = $this->container->getParameter('l_soft_ad.lifetime');
        // check object class
        if($object instanceof Ad)
        {   // get data by AD
            $adsDates = $em->getRepository("LSoftAdBundle:Ad")->findParentById($object->getId());
        }
        elseif($object instanceof AdsProvider)
        {   // get data by AdsProvider
            $adsDates[] = $object;
        }
        elseif($object instanceof Domain)
        {   // get data by Domain
            $adsDates = $em->getRepository("LSoftAdBundle:Ad")->findParentByDomain($object->getId());
        }

        $ads = array();

        foreach($adsDates as $data)
        {
            $ads[$data->getId()]['ad']= $data->getAd();
            $ads[$data->getId()]['zone']= $data->getZone();

            foreach($data->getDomain() as $domain)
            {
                $domainName = $domain->getName();
                if(isset($domainName) && $domainName != null);

                $kay = $this->cretePattern($domainName, $data->getZone());

                $ad = $data->getAd();

               if( $ad != null)
               {
                   if(extension_loaded('apc') && ini_get('apc.enabled'))
                    {
                        apc_delete((string)$kay);
                        apc_add((string)$kay, $ad, $lifetime);
                    }
               }
            }
        }
    }

    /**
     * This function generate kay for apc cache
     *
     * @param $domain
     * @param $zone
     * @return string
     */
    private function cretePattern($domain, $zone)
    {
        //get pattern from configs
        $pattern = $this->container->getParameter('l_soft_ad.pattern');
        // get kay for apc cache
        $key = str_replace(' ', '_', $pattern).'_'.str_replace(' ', '_', $domain).'_'.str_replace(' ', '_', $zone);

        return $key;
    }

    private function checkAnalytics($adId)
    {
        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $adAnalytic = $em->getRepository('LSoftAdBundle:AdsAnalytics')->findOneByAdId($adId);

        if(!$adAnalytic)
        {
            $adAnalytic = new  AdsAnalytics();
            $adAnalytic->setAdId($adId);
            $adAnalytic->setVisits(1);
            $em->persist($adAnalytic);
        }
        else
        {
            $adAnalytic->setVisits($adAnalytic->getVisits() + 1);
            $em->persist($adAnalytic);
            $oldDate = date_timestamp_get($adAnalytic->getCreated());
            $now = date_timestamp_get(new \DateTime('now'));

            $timeStep = $now - $oldDate;
            $timeStepConfig = $this->container->getParameter('l_soft_ad.analytics');

            if($timeStepConfig != false && $timeStep > (int)$timeStepConfig)
            {
                    $ad = $em->getRepository('LSoftAdBundle:Ad')->find($adId);

                    $adAnalyticsProvider = new AdAnalyticsProvider();

                    $adAnalyticsProvider->setVisits($adAnalytic->getVisits());
                    $adAnalyticsProvider->setAd($ad);

                $em->persist($adAnalyticsProvider);
                $em->remove($adAnalytic);
            }
        }

        $em->flush();
    }

}