<?php

namespace Softspring\AccountBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AliasDoctrineEntityManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $emName = $container->getParameter('sfs_account.entity_manager_name');

        $container->addAliases([
            'sfs_account.entity_manager' => 'doctrine.orm.'.$emName.'_entity_manager',
        ]);
    }
}
