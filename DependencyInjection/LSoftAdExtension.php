<?php

namespace LSoft\AdBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $googleAccount = $container->getParameter('google_analytics_account_id');
        $googleView = $container->getParameter('google_analytics_view_id');


            // analytics part
            if (isset($config) && isset($config['analytics']) && is_numeric($config['analytics'])) {
                if($googleAccount != null &&  $googleView != null)
                {
                    $container->setParameter($this->getAlias() . '.analytics', $config['analytics']);

                    $loader->load('analytics.xml');
                }
                else {
                    throw new NotFoundHttpException('Please check google_analytics_account_id and google_analytics_view_id in parameters.yml. Analytics dependency of google analytics');
                }
            }
            else {
                //if analytics in config is missing add default analytics false
                $container->setParameter($this->getAlias() . '.analytics', false);
            }

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
    }
}
