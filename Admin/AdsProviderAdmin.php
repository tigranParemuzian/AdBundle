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
        $this->addAndRemoveDomain($object);
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($object)
    {
        $this->addAndRemoveDomain($object);
    }


    /**
     * @param $object
     */
    private function addAndRemoveDomain(&$object)
    {
        // get domains
        $domains = $object->getDomain();
        foreach($domains as $domain) {

            if (!$domain->getAdsProvider()->contains($object)) {
                $domain->addAdsProvider($object);
            }
        }

        if(method_exists($domains, 'getDeleteDiff')){
            $deleted = $domains->getDeleteDiff();
            foreach($deleted as $delete) {
                $object->removeDomain($delete);
                $delete->removeAdsProvider($object);
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