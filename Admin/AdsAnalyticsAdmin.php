<?php
/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 1/13/16
 * Time: 4:26 PM
 */

namespace LSoft\AdBundle\Admin;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

class AdsAnalyticsAdmin extends Admin
{
    protected $baseRoutePattern = 'ads-statistic';
    protected $baseRouteName = 'ads-statistic';

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list'));
    }

}