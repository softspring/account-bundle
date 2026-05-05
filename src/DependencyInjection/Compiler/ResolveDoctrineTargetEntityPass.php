<?php

namespace Softspring\AccountBundle\DependencyInjection\Compiler;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipInterface;
use Softspring\Component\DoctrineTargetEntityResolver\DependencyInjection\Compiler\AbstractResolveDoctrineTargetEntityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ResolveDoctrineTargetEntityPass extends AbstractResolveDoctrineTargetEntityPass
{
    protected function getEntityManagerName(ContainerBuilder $container): string
    {
        return $container->getParameter('sfs_account.entity_manager_name');
    }

    public function process(ContainerBuilder $container): void
    {
        $this->setTargetEntityFromParameter('sfs_account.account.class', AccountInterface::class, $container, true);
        $this->setTargetEntityFromParameter('sfs_account.membership.class', AccountMembershipInterface::class, $container, false);
    }
}
