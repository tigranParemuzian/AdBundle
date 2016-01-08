<?php

namespace LSoft\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\DataCollector\EventListener\DataCollectorListener;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction(Request $request, $domain, $zone)
    {

        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get kay for apc cache
        $key = $request->attributes->get('_controller').'_'.str_replace(' ', '_', $domain).'_'.str_replace(' ', '_', $zone);

        // check apc php apc is enable
        if(extension_loaded('apc') && ini_get('apc.enabled'))
        {
            // check data in apc by kay
            $data = apc_fetch($key);
            // set timeout for apc cache
            $ttl = 86400;

            if(!$data)
            {
                // get ad manager
                $ad = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $zone);
                // add data in apc timeout 1 day
                $data = apc_add($key, $ad, $ttl);
            }
        }
        else
        {
            // if cache not exist get data
            $data = $em->getRepository("LSoftAdBundle:Ad")->findByAdsManager($domain, $zone);
        }

        // set data for profiler
        $this->container->get('data_collector.ad_collector')->addData($domain, $zone, $data);
        // return data
        return array('ads' => $data, 'zone' => $zone);
    }
}
