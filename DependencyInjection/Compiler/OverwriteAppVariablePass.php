<?php

namespace Softspring\AccountBundle\DependencyInjection\Compiler;

use Softspring\AccountBundle\Templating\GlobalVariables;
use Softspring\AccountBundle\Twig\AppVariable;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverwriteAppVariablePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('twig.app_variable')) {
            $definition = $container->getDefinition('twig.app_variable');
            $definition->setClass(AppVariable::class);
        }

        if ($container->hasDefinition('templating.globals')) {
            $definition = $container->getDefinition('templating.globals');
            $definition->setClass(GlobalVariables::class);
        }
    }
}