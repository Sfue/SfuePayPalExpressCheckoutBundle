<?php

namespace Sfue\PayPalExpressCheckoutBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfue_pay_pal_express_checkout');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode->children()
            ->scalarNode('merchant_email')->isRequired()->end()
            ->booleanNode('sandbox')->isRequired()->end()
            ->scalarNode('ec_user_name')->isRequired()->end()
            ->scalarNode('ec_ec_password')->isRequired()->end()
            ->scalarNode('ec_ec_signature')->isRequired()->end()
            ->scalarNode('default_currency')->defaultValue('EUR')->end();

        return $treeBuilder;
    }
}