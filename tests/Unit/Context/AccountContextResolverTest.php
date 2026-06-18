<?php

namespace Softspring\AccountBundle\Tests\Unit\Context;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Context\AccountContextResolver;
use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountContextResolverTest extends TestCase
{
    public function testItReturnsResolvedAccountFromRequestAttributes(): void
    {
        $account = new AccountStub('account-42');
        $manager = $this->createMock(AccountManagerInterface::class);
        $resolver = new AccountContextResolver($manager);
        $request = new Request();
        $request->attributes->set('_account', $account);

        self::assertTrue($resolver->hasAccountScope($request));
        self::assertSame($account, $resolver->resolveAccount($request));
    }

    public function testItLoadsAccountUsingConfiguredRouteParameterAndField(): void
    {
        $account = new AccountStub('account-42');
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['slug' => 'main-account'])
            ->willReturn($account);

        $manager = $this->createMock(AccountManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new AccountContextResolver($manager, '_workspace', 'slug');
        $request = new Request();
        $request->attributes->set('_workspace', 'main-account');

        self::assertSame($account, $resolver->resolveAccount($request));
        self::assertSame($account, $request->attributes->get('_workspace'));
    }

    public function testItReturnsNullWhenAccountCannotBeResolved(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 'missing-account'])
            ->willReturn(null);

        $manager = $this->createMock(AccountManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new AccountContextResolver($manager);
        $request = new Request();
        $request->attributes->set('_account', 'missing-account');

        self::assertNull($resolver->resolveAccount($request));
    }
}

class AccountStub implements AccountInterface
{
    public function __construct(private readonly string $id)
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return 'Account';
    }

    public function setName(?string $name): void
    {
    }
}
