<?php

namespace Softspring\AccountBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Softspring\AccountBundle\DependencyInjection\Compiler\AliasDoctrineEntityManagerPass;
use Softspring\AccountBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsAccountBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $basePath = realpath(__DIR__.'/../config/doctrine-mapping/');

        $this->addRegisterMappingsPass($container, [$basePath => 'Softspring\AccountBundle\Model']);

        $container->addCompilerPass(new AliasDoctrineEntityManagerPass());
        $container->addCompilerPass(new ResolveDoctrineTargetEntityPass());
    }

    /**
     * @param string|bool $enablingParameter
     */
    private function addRegisterMappingsPass(ContainerBuilder $container, array $mappings, $enablingParameter = false)
    {
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['sfs_account.entity_manager_name'], $enablingParameter));
    }
}
