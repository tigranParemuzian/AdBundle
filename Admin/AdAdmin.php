<?php

namespace LSoft\AdBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Class AdAdmin
 * @package LSoft\AdBundle\Admin
 */
class AdAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('code')
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
            ->add('name')
            ->add('code', 'textarea', array('required' => false))
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