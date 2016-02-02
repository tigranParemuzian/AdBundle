<?php

namespace LSoft\AdBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


/**
 * Class AdsProviderAdmin
 * @package LSoft\AdBundle\Admin
 */
class AdsProviderAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('domain')
            ->add('ad')
            ->add('zone')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('domain')
            ->addIdentifier('ad')
            ->addIdentifier('zone')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
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
            ->add('domain')
            ->add('ad')
            ->add('zone')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('domain')
            ->add('ad')
            ->add('zone')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($object)
    {
        $zone = $object->getZone();
        if($zone)
        {
            $zone = str_replace(' ', '_', strtolower($zone));

            $object->setZone($zone);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $zone = $object->getZone();
        if($zone)
        {
            $zone = str_replace(' ', '_', strtolower($zone));

            $object->setZone($zone);
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