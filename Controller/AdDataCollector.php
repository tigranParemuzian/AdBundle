<?php

namespace LSoft\AdBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class AdDataCollector extends DataCollector
{

    /**
     * @param Request $request
     * @param Response $response
     * @param \Exception|null $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        
    }

    /**
     * return data for profiler
     * @return array
     */
    public function getAdsData()
    {
        // return data
        return $this->data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ad_collector';
    }

    /**
     * create data for profiler data collector
     *
     * @param $domain
     * @param $zone
     * @param $data
     */
    public function addData($domain, $zone, $data)
    {
        $this->data[]= array('ad'=>$data, 'domain'=>$domain, 'zone'=>$zone);
    }
}