<?php

namespace Softspring\AccountBundle\Tests\Integration\DependencyInjection;

use Doctrine\Bundle\FixturesBundle\Fixture;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\DataFixtures\AccountFixtures;
use Softspring\AccountBundle\DependencyInjection\SfsAccountExtension;
use Softspring\AccountBundle\EventListener\AccountDoctrineFilterListener;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Request\AccountValueResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SfsAccountExtensionTest extends TestCase
{
    public function testLoadRegistersConfiguredParametersAndOptionalServices(): void
    {
        $container = new ContainerBuilder();
        $extension = new SfsAccountExtension();

        $extension->load([[
            'class' => 'App\\Entity\\Workspace',
            'membership_class' => 'App\\Entity\\WorkspaceMember',
            'entity_manager' => 'accounts',
            'twig_app_var_name' => 'workspace',
            'route_param_name' => '_workspace',
            'find_field_name' => 'slug',
            'admin' => false,
            'filter' => ['enabled' => false],
        ]], $container);

        self::assertSame('accounts', $container->getParameter('sfs_account.entity_manager_name'));
        self::assertSame('App\\Entity\\Workspace', $container->getParameter('sfs_account.account.class'));
        self::assertSame('App\\Entity\\WorkspaceMember', $container->getParameter('sfs_account.membership.class'));
        self::assertSame('_workspace', $container->getParameter('sfs_account.account.route_param_name'));
        self::assertSame('slug', $container->getParameter('sfs_account.account.find_field_name'));
        self::assertSame('workspace', $container->getParameter('sfs_account.account.twig_app_var_name'));

        self::assertFalse($container->hasDefinition('sfs_account.admin.account.controller'));
        self::assertFalse($container->hasDefinition(AccountDoctrineFilterListener::class));
        self::assertTrue($container->hasDefinition(AccountValueResolver::class));
        self::assertSame(class_exists(Fixture::class), $container->hasDefinition(AccountFixtures::class));
    }

    public function testPrependRegistersDoctrineTargetEntityAndTwigExtraConfig(): void
    {
        $container = new ContainerBuilder();
        $extension = new SfsAccountExtension();

        $extension->prepend($container);

        $doctrineConfig = $container->getExtensionConfig('doctrine');
        $twigExtraConfig = $container->getExtensionConfig('sfs_twig_extra');

        self::assertSame('App\\Entity\\Account', $doctrineConfig[0]['orm']['resolve_target_entities'][AccountInterface::class]);
        self::assertFalse($doctrineConfig[0]['orm']['mappings']['SfsAccountBundle']['mapping']);
        self::assertTrue($doctrineConfig[0]['orm']['mappings']['SfsAccountBundle']['is_bundle']);
        self::assertTrue($twigExtraConfig[0]['instanceof_extension']);
    }
}
