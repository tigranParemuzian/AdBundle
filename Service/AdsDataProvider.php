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

        if(isset($this->adsData[$zone]))
        {
            $data = $this->adsData[$zone];
        }
        else
        {
            $ads = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $key, $lifetime);

            if(count($ads)>0)
            {
                foreach($ads as $ad)
                {
                    if($ad['zone'])
                    {
                        $this->adsData[$ad['zone']] = $ad['ad'];

                        if($ad['zone'] == $zone)
                        {
                            $data = $this->adsData[$ad['zone']];
                        }
                    }
                }
            }
        }

        if ($data != null) {
            $this->adSingle[] = array('ad_name' => $data->getName(), 'domain' => $domain, 'zone' => $zone);
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
            // get data by AD
            $adsDates = $em->getRepository("LSoftAdBundle:Ad")->findParentById($object->getId());
        } elseif ($object instanceof AdsProvider) {
            // get data by AdsProvider
            $adsDates[] = $object;
        } elseif ($object instanceof Domain) {
            // get data by Domain
            $adsDates = $em->getRepository("LSoftAdBundle:Ad")->findParentByDomain($object->getId());
        }

        $ads = array();

        foreach ($adsDates as $data) {
            $ads[$data->getId()]['ad'] = $data->getAd();
            $ads[$data->getId()]['zone'] = $data->getZone();

            foreach ($data->getDomain() as $domain) {
                $domainName = $domain->getName();
                if (isset($domainName) && $domainName != null) ;

                $kay = $this->cretePattern($domainName, $data->getZone());

                $ad = $data->getAd();

                if ($ad != null) {
                    $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $kay, $lifetime);
                }
            }
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