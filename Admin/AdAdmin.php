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
            ->addIdentifier('dimensionIndex')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'show' => array(),
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
            ->add('name')
            ->add('code', 'textarea', array('required' => false))
            ->add('dimensionIndex', 'number', array('required' => false))
            ->setHelps(array(
                'dimensionIndex' => 'Set the google <a href="https://support.google.com/analytics/answer/6164990?hl=en" target="_blank"> Analytics dimension </a>index of a ad page',
            ))

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('codes')
            ->add('dimensionIndex')
        ;
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