<?php

namespace LSoft\AdBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    public function indexAction($domain, $zone)
    {
        $zone = str_replace(' ', '_', strtolower($zone));
        // get data
        $data = $this->container->get('lsoft.ads.check_data')->checkData($domain, $zone);

        // set data for profiler
        $this->container->get('data_collector.ad_collector')->addData($domain, $zone, $data);
        // return data
        return array('ads' => $data, 'zone' => $zone);
    }

    /**
     *
     * @Route("/remove-image/{filename}/{object}", name="l_soft_remove_image_ad")
     * @Security("has_role('ROLE_SUPER_ADMIN', 'ROLE_ADMIN')")
     * @param $filename
     * @param $object
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function removeImageAction($filename, $object)
    {
        try{
            // get entity manager
            $em = $this->getDoctrine()->getManager();

            // get object by className
            $object = $em->getRepository($object)->findOneBy(array('fileName' => $filename));

            // get origin file path
            $filePath = $object->getAbsolutePath() . $object->getFileName();

            // get doctrine
            $em = $this->getDoctrine()->getManager();

            // check file and remove
            if (file_exists($filePath) && is_file($filePath)){
                unlink($filePath);
            }

            $object->setFileName(null);
            $object->setFileOriginalName(null);

            $em->persist($object);
            $em->flush();

            return $this->redirect($_SERVER['HTTP_REFERER']);
        }
        catch(\Exception $e){
            throw $e;
        }

    }
}
