<?php

namespace Softspring\AccountBundle\DependencyInjection\Compiler;

use Softspring\Account\Model\AccountInterface;
use Softspring\Account\Model\AccountUserRelationInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class ResolveDoctrineTargetEntityPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // configure account entity
        $accountClass = $container->getParameter('sfs_account.account.class');
        if (!class_implements($accountClass, AccountInterface::class)) {
            throw new LogicException(sprintf('%s class must implements %s interface', $accountClass, AccountInterface::class));
        }
        $this->setTargetEntity($container, AccountInterface::class, $accountClass);


        if ($relationClass = $container->getParameter('sfs_account.relation.class')) {
            if (!class_implements($relationClass, AccountUserRelationInterface::class)) {
                throw new LogicException(sprintf('%s class must implements %s interface', $relationClass, AccountUserRelationInterface::class));
            }
            $this->setTargetEntity($container, AccountUserRelationInterface::class, $relationClass);
        }
    }

    private function setTargetEntity(ContainerBuilder $container, string $interface, string $class)
    {
        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_subscriber')) {
            $resolveTargetEntityListener->addTag('doctrine.event_subscriber');
        }

        $resolveTargetEntityListener->addMethodCall('addResolveTargetEntity', [$interface, $class, []]);
    }
}