<?php

namespace Softspring\AccountBundle\DependencyInjection;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SfsAccountExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        // set config parameters
        $container->setParameter('sfs_account.entity_manager_name', $config['entity_manager']);
        $container->setParameter('sfs_account.account.class', $config['class']);
        $container->setParameter('sfs_account.account.route_param_name', $config['route_param_name']);
        $container->setParameter('sfs_account.account.find_field_name', $config['find_field_name']);
        $container->setParameter('sfs_account.relation.class', $config['relation_class']);

        // load services
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config/services'));
        $loader->load('services.yaml');
        $loader->load('controller/admin_accounts.yaml');
        $loader->load('controller/register.yaml');
        $loader->load('controller/settings.yaml');
        $loader->load('controller/user.yaml');

        if ($config['filter']['enabled']) {
            $loader->load('doctrine_filter.yaml');
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $doctrineConfig = [];

        // add a default config to force load target_entities, will be overwritten by ResolveDoctrineTargetEntityPass
        $doctrineConfig['orm']['resolve_target_entities'][AccountInterface::class] = 'App\Entity\Account';

        // disable auto-mapping for this bundle to prevent mapping errors
        $doctrineConfig['orm']['mappings']['SfsAccountBundle'] = [
            'is_bundle' => true,
            'mapping' => false,
        ];

        $container->prependExtensionConfig('doctrine', $doctrineConfig);

        $container->prependExtensionConfig('sfs_core', [
            'twig' => [
                'instanceof_extension' => true,
            ],
        ]);
    }
}
