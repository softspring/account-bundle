<?php

namespace Softspring\AccountBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sfs_account');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('class')
                    ->defaultValue('App\Entity\Account')
                ->end()
                ->booleanNode('admin')->defaultTrue()->end()

                ->scalarNode('entity_manager')
                    ->defaultValue('default')
                ->end()

                ->scalarNode('twig_app_var_name')->defaultValue('account')->end()
                ->scalarNode('route_param_name')->defaultValue('_account')->end()
                ->scalarNode('find_field_name')->defaultValue('id')->end()

                ->scalarNode('relation_class')
                    ->defaultNull()
                ->end()

                ->arrayNode('filter')
                    ->canBeEnabled()
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
