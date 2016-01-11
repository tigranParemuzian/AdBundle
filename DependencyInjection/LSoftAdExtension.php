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

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (isset($config) && isset($config['pattern'])) {
            $container->setParameter($this->getAlias() . '.pattern', $config['pattern']);
        }
        else {
            $baseUrl = 'LSoft';
            $container->setParameter($this->getAlias() . '.pattern', $baseUrl);
        }

        if (isset($config) && isset($config['lifetime']) && is_numeric($config['lifetime'])) {
            $container->setParameter($this->getAlias() . '.lifetime', $config['lifetime']);
        }
        else {

            $container->setParameter($this->getAlias() . '.lifetime', 86400);
        }
    }
}
