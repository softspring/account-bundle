<?php

namespace Softspring\AccountBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Softspring\AccountBundle\DependencyInjection\Compiler\OverwriteAppVariablePass;
use Softspring\AccountBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntityPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfsAccountBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $basePath = realpath(__DIR__.'/Resources/config/doctrine-mapping/');

        $this->addRegisterMappingsPass($container, [$basePath => 'Softspring\Account\Model']);

        $container->addCompilerPass(new ResolveDoctrineTargetEntityPass());
        $container->addCompilerPass(new OverwriteAppVariablePass());
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $mappings
     * @param string|bool      $enablingParameter
     */
    private function addRegisterMappingsPass(ContainerBuilder $container, array $mappings, $enablingParameter = false)
    {
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['sfs_user_em'], $enablingParameter));
    }
}