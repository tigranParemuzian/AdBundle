<?php
/**
 * Created by PhpStorm.
 * User: tigran
 * Date: 1/13/16
 * Time: 4:40 PM
 */

namespace LSoft\AdBundle\Controller\Admin;


use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use LSoft\AdBundle\Form\AdsAnalyticsFormType;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdsAnalyticsController extends Controller
{

    const DAYS = 1;
    const WEEK = 2;
    const MONTH = 3;
    const YEAR = 4;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        // get entity manager
        $em = $this->container->get('doctrine')->getManager();

        $ads = $em->getRepository('LSoftAdBundle:Ad')->findAnalytics();

        $chartData = null;
        if(count($ads)>0)
        {
            $chartData = $this->createChartData($ads);
        }

//        dump($chartData); exit;
        $request = $this->container->get('request');

        $form = $this->createForm( new AdsAnalyticsFormType());

        if($request->isMethod('POST')) {
            // get request & check
            $form->handleRequest($request);
            if ($form->isValid()) {
                // form get date
                $data = $form->getData();
                $state = $form->getClickedButton()->getConfig()->getName();

                if($state == 'filter')
                {
                    $ads = $data['ad'];
                    $from = $data['from'];
                    $to = $data['to'];
                    $chartType = $data['chart_type'];

                    if(count($ads)>0)
                    {

                        $chartData = $this->createChartData($ads, $from, $to, $chartType);
                    }


//                    dump($chartData); exit;


//                    $adData = $em->getRepository('LSoftAdBundle:Ad')->find($data['ad']->getId());

                }
            }
        }



        return $this->render('LSoftAdBundle:Admin:analytics.html.twig', array(
            'form'=>$form->createView(), 'chartData' => $chartData

        ));
    }

    protected function createChartData($data, $from = null, $to = null, $chartType = null)
    {

        $category = array();
        $minYear = 2015;
        $maxYear =  $now = (new \DateTime('now'))->format('Y');

        $chartData = array('category'=>array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                                                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
                            'data'=>array(0=>array('name'=>'name',
                                'values'=>array(1 , 0, 0, 12, null, 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')),
                                        1=>array('name'=>'name',
                                'values'=>array(1 , 0, 0, 12, null, 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')),
                                        2=>array('name'=>'name',
                                'values'=>array(1 , 0, 0, 12, null, 'Jun',
                                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'))),);
        $categories = array();
        foreach($data as $ad)
        {
            $adsData['data'][$ad->getId()]['id'] = $ad->getId();
            $adsData['data'][$ad->getId()]['name'] = $ad->getName();
            $adsData['data'][$ad->getId()]['day'] = array();
            $adsData['data'][$ad->getId()]['year'] = array();
            $adsData['data'][$ad->getId()]['month'] = array();

            $analytics = $ad->getAdAnalytics();



            if($from != null)
            {

                if(count($analytics)>0)
                {
                    $criteriaFrom = Criteria::create()
                        ->andWhere(Criteria::expr()->gte("created", $from));

                    $analytics = $analytics->matching($criteriaFrom);
                }
            }

            if($to != null)
            {
                $criteriaFrom = Criteria::create()
                    ->andWhere(Criteria::expr()->lte("created", $to));

                $analytics = $analytics->matching($criteriaFrom);
            }

            if(count($analytics)>0)
            {
                $i = 0;
                foreach($analytics as $analytic)
                {
                    $categories['year'][] = $analytic->getCreated()->format('Y');

                    $minYear = min($categories['year']);
                    $maxYear = max($categories['year']);

//                    $chartData['data'][$ad->getId()]['year']['values'] =    ;
                    $categories['month'][] = $analytic->getCreated()->format('M');

                    $day = $analytic->getCreated()->format('d');

//                    if(array_key_exists($day, $adsData['data'][$ad->getId()]['day']))
//                    {
//                        $adsData['data'][$ad->getId()]['day'][$day]['value'] = $adsData['data'][$ad->getId()]['day'][$day]['value'] + $analytic->getVisits();
//                    }
//                    else
//                    {
//                        $adsData['data'][$ad->getId()]['day'][$day][] = $day;
//                        $adsData['data'][$ad->getId()]['day'][$day]['value'] = $analytic->getVisits();
//                    }

//                    $year = $analytic->getCreated()->format('Y');
//
//                    if(array_key_exists($year, $adsData['data'][$ad->getId()]['year']))
//                    {
//                        $adsData['data'][$ad->getId()]['year'][$year]['value'] = $adsData['data'][$ad->getId()]['year'][$year]['value'] + $analytic->getVisits();
//
//                    }
//                    else
//                    {
//                        $adsData['data'][$ad->getId()]['year'][$year][] = $year;
//                        $adsData['data'][$ad->getId()]['year'][$year]['value'] = $analytic->getVisits();
//                    }
//

//                    $adsData['data'][$ad->getId()]['day'][$analytic->getCreated()->format('d')] = $analytic->getCreated()->format('d');

                    $createrd = $analytic->getCreated()->format('Y-m-d');
                    $adsData['data'][$ad->getId()][$createrd][] = $analytic->getCreated()->format('Y-m-d');
                    $adsData['data'][$ad->getId()][$createrd]['visits'] = $analytic->getVisits();
//                    $adsData['data'][$ad->getId()]['day'][] = $analytic->getCreated()->format('d');
//                    $adsData['data'][$ad->getId()]['year'][] = $analytic->getCreated()->format('Y');
//                    $adsData['data'][$ad->getId()]['month'][] = $analytic->getCreated()->format('M');
                    $adsData['data'][$ad->getId()]['visits'][] = $analytic->getVisits();

                    $i++ ;
                }
            }
        }

        $years = array('maxYear'=>$maxYear, 'minYear'=>$minYear);
//        dump($minYear);
//        dump($adsData); exit;
            switch($chartType)
            {
                case  (AdsAnalyticsController::YEAR):

                    $chartData['categories'] = $this->getByYear($years, $from, $to);
                    foreach($chartData['categories'] as $year)
                    {
                        foreach($adsData as $ad)
                        {
                            $chartData['data'][$ad['id']]['name'] = $ad['name'];

                            foreach($ad['analytics'] as $analytics)
                            {
                                $chartData['data'][$ad['id']]['visits'] = null;
                                foreach($analytics as $analytic)
                                {

                                    if($analytic['year'] == $year)
                                    {
                                        $chartData['data'][$ad['id']]['visits'] = $chartData['data'][$ad['id']]['visits'] + $ad['visits'];
                                    }
                                    else
                                    {

                                    }
                                }

                            }
                        }
                    }

                    break;
                case (AdsAnalyticsController::MONTH) :
                    $chartData['categories'] =   array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
                    break;
                case (AdsAnalyticsController::DAYS):
                    $chartData['categories'] = array_unique($categories['day']);
                    array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

                    $chartData['categories'] = array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa');
                    break;
                default:
                    break;
            }


        return $chartData;
    }

    public function getByYear($years, $from = null, $to = null)
    {

        if($from != null && $to != null)
        {
            $categories = range($from->format('Y'), $to->format('Y'));
        }
        elseif($from != null && $to == null)
        {
            $now = new \DateTime('now');
            $categories = range($from->format('Y'), $now->format('Y'));
        }
        elseif($from == null && $to != null)
        {
            $categories = range(($years['minYear']), $to->format('Y'));

        }
        else
        {
            $categories = range($years['minYear'], $years['maxYear']);
        }


        return $categories;
    }
}