<?php

namespace LSoft\AdBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LSoftAdExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        // load bundle default services
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        // load bundle default configs
        if (isset($config) && isset($config['pattern'])) {
            $container->setParameter($this->getAlias() . '.pattern', $config['pattern']);
        }
        else {
            // if pattern in configs is missing set default pattern LSoft
            $baseUrl = 'LSoft';
            $container->setParameter($this->getAlias() . '.pattern', $baseUrl);
        }

        if (isset($config) && isset($config['lifetime']) && is_numeric($config['lifetime'])) {
            $container->setParameter($this->getAlias() . '.lifetime', $config['lifetime']);
        }
        else {
            //if lifetime in config is missing add default lifetime 1 day
            $container->setParameter($this->getAlias() . '.lifetime', 86400);
        }
            // analytics part
        if (isset($config) && isset($config['analytics']) && is_numeric($config['analytics'])) {
            $container->setParameter($this->getAlias() . '.analytics', $config['analytics']);
        }
        else {
            //if analytics in config is missing add default analytics false
            $container->setParameter($this->getAlias() . '.analytics', false);
        }
    }
}
