<?php

namespace Sfue\PayPalExpressCheckoutBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SfuePayPalExpressCheckoutExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sfue.paypal.merchant_email', $config['merchant_email']);
        $container->setParameter('sfue.paypal.user_name', $config['ec_user_name']);
        $container->setParameter('sfue.paypal.password', $config['ec_ec_password']);
        $container->setParameter('sfue.paypal.signature', $config['ec_ec_signature']);
        $container->setParameter('sfue.paypal.sandbox', $config['sandbox']);
        $container->setParameter('sfue.paypal.default_currency', $config['default_currency']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
