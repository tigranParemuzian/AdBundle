<?php

namespace LSoft\AdBundle\Service;

use LSoft\AdBundle\Entity\Ad;
use LSoft\AdBundle\Entity\AdsProvider;
use LSoft\AdBundle\Entity\Domain;
use Symfony\Component\DependencyInjection\Container;

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

        return $data;
    }

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

    private function cretePattern($domain, $zone)
    {
        //get pattern from configs
        $pattern = $this->container->getParameter('l_soft_ad.pattern');
        // get kay for apc cache
        $key = str_replace(' ', '_', $pattern).'_'.str_replace(' ', '_', $domain).'_'.str_replace(' ', '_', $zone);

        return $key;
    }



}