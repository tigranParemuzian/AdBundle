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
        // get data
        $data = $this->container->get('lsoft.ads.check_data')->checkData($domain, $zone);

        // set data for profiler
        $this->container->get('data_collector.ad_collector')->addData($domain, $zone, $data);
        // return data
        return array('ads' => $data, 'zone' => $zone);
    }
}
