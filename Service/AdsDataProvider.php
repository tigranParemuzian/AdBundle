<?php

namespace LSoft\AdBundle\Service;

use LSoft\AdBundle\Entity\Ad;
use LSoft\AdBundle\Entity\AdAnalyticsProvider;
use LSoft\AdBundle\Entity\AdsAnalytics;
use LSoft\AdBundle\Entity\AdsProvider;
use LSoft\AdBundle\Entity\Domain;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class AdsService
 * @package LSoft\AdBundle\Service
 */
class AdsDataProvider
{

    private $adsData = array();

    private $adSingle;
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
        $key = $this->cretePattern($domain);

        //get lifetime from caching
        $lifetime = $this->container->getParameter('l_soft_ad.lifetime');

        $data = null;

        $ads = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $key, $lifetime);

        if(isset($this->adsData[$zone]))
        {
            $data = $this->adsData[$zone];
        }
        else
        {
            // get data bu domain
            $ads = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $key, $lifetime);

            if($ads && count($ads)>0)
            {
                // set data in array by zone and domain
                foreach($ads as $ad)
                {
                    if($ad->getZone() != null)
                    {
                        $this->adsData[$ad->getZone()] = $ad->getAd();

                        if($ad->getZone() == $zone)
                        {
                            $data = $this->adsData[$ad->getZone()];
                        }
                    }
                }
            }
        }

        if ($data != null) {
            //
            $this->adSingle[] = array('ad_name' => $data->getName(), 'index'=>(int)$data->getDimensionIndex(), 'domain' => $domain, 'zone' => $zone);
            $session = $this->container->get('session');
            $session->set('adData', $this->adSingle);
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
        if ($object instanceof Ad) {
            // get domains by ad id
            $adsDomains = $em->getRepository("LSoftAdBundle:Ad")->findParentById($object->getId());

            if(count($adsDomains) != null)
            {
                // update doctrine cache by domains
                foreach($adsDomains as $adsDomain)
                {
                    $domain = $adsDomain['name'];
                    $key = $this->cretePattern($domain);
                    $update = true;
                    $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $key, $lifetime, $update);
                }
            }

        } elseif ($object instanceof AdsProvider) {
            // get domains by Ads Provider id
            $adsDomains = $em->getRepository("LSoftAdBundle:Ad")->findDomainsByProvider($object->getId());

            if(count($adsDomains) != null)
            {
                foreach($adsDomains as $adsDomain)
                {
                    // update doctrine cache by domains
                    $domain = $adsDomain['name'];
                    $key = $this->cretePattern($domain);
                    $update = true;
                    $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $key, $lifetime, $update);
                }
            }

        } elseif ($object instanceof Domain) {
            // get domain name for update cache
            $domain = $object->getName();
            $key = $this->cretePattern($domain);
            $update = true;
            $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $key, $lifetime, $update);
        }
    }

    /**
     * This function generate kay for apc cache
     *
     * @param $domain
     * @return string
     */
    public function cretePattern($domain)
    {
        //get pattern from configs
        $pattern = $this->container->getParameter('l_soft_ad.pattern');
        // get kay for apc cache
        $key = str_replace(' ', '_', $pattern) . '_' . str_replace(' ', '_', $domain);

        return $key;
    }
}