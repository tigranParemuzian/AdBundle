<?php

namespace LSoft\AdBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class AdAdmin
 * @package LSoft\AdBundle\Admin
 */
class AdAdmin extends Admin
{
    /**
     * @var bool
     */
    public $supportsPreviewMode = true;
    /**
     * @param string $name
     * @return null|string
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'preview':
                return 'LSoftAdBundle:Admin:preview_ad.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('url')
            ->add('dimensionIndex')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('code')
            ->addIdentifier('url')
            ->addIdentifier('dimensionIndex')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('General')
            ->with('General')
            ->add('name')
                ->add('dimensionIndex', 'number', array('required' => false))
                ->end()
            ->end()
            ->tab('Ad code data')
            ->with('Ad code data')
                ->add('code', 'textarea', array('required' => false))
                ->end()
            ->end()
            ->tab('Custom Ad data')
            ->with('Custom Ad data')
                ->add('url', 'url', array('required' => false))
                ->add('file', 'ad_file_type', array('required' => false, 'label'=>'Ad image'))
                ->end()
            ->end()
                ->setHelps(array(
                    'dimensionIndex' => 'Set the google <a href="https://support.google.com/analytics/answer/6164990?hl=en" target="_blank"> Analytics dimension </a>index of a ad page',
                    'url' => 'This field url of custom ad',
                    'file' => 'This field image of custom ad',
                    'code' => 'This field code of Ad ',
                ))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $analytics = $container->getParameter('l_soft_ad.analytics');

        if($analytics == true)
        {
            if(!$object->getDimensionIndex())
            {
                return;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $analytics = $container->getParameter('l_soft_ad.analytics');

        if($analytics == true)
        {
            if(!$object->getDimensionIndex())
            {
                return;
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function postPersist($object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $container->get('lsoft.ads.check_data')->updateApc($object);
    }

    /**
     * {@inheritdoc}
     */
    public function postUpdate($object)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $container->get('lsoft.ads.check_data')->updateApc($object);
    }
}