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
        $em = $this->getDoctrine()->getManager();
        // get all ads for analytics
        $ads = $em->getRepository('LSoftAdBundle:Ad')->findAllForAnalytics();
        // get parameters from parameters.yml for login
        $googleAccount = $this->container->getParameter('google_analytics_account_id');
        $googleView = $this->container->getParameter('google_analytics_view_id');

        return $this->render('LSoftAdBundle:Admin:analytics.html.twig',
            array( 'googleAccount' => $googleAccount, 'googleView'=>$googleView, 'ads'=>$ads));
    }
}