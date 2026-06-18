<?php

namespace Softspring\AccountBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Context\AccountContextResolverInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Request\AccountValueResolver;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AccountValueResolverTest extends TestCase
{
    public function testSkipsArgumentsThatAreAlreadyResolved(): void
    {
        $accountContextResolver = $this->createMock(AccountContextResolverInterface::class);
        $resolver = new AccountValueResolver($accountContextResolver);
        $request = new Request();
        $request->attributes->set('account', new stdClass());

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testSkipsUnsupportedArgumentTypes(): void
    {
        $accountContextResolver = $this->createMock(AccountContextResolverInterface::class);
        $resolver = new AccountValueResolver($accountContextResolver);
        $request = new Request();
        $request->attributes->set('_account', 'account-42');

        $resolved = $resolver->resolve($request, new ArgumentMetadata('account', stdClass::class, false, false, null));

        self::assertSame([], $resolved);
    }

    public function testReturnsEmptyResultWhenRouteParameterIsMissing(): void
    {
        $accountContextResolver = $this->createMock(AccountContextResolverInterface::class);
        $accountContextResolver->expects(self::once())
            ->method('resolveAccount')
            ->willReturn(null);
        $resolver = new AccountValueResolver($accountContextResolver);

        $resolved = $resolver->resolve(new Request(), $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testResolvesAccountUsingConfiguredRouteParameterAndFindField(): void
    {
        $account = new AccountStub('account-42', 'Main account');
        $accountContextResolver = $this->createMock(AccountContextResolverInterface::class);
        $accountContextResolver->expects(self::once())
            ->method('resolveAccount')
            ->willReturn($account);
        $resolver = new AccountValueResolver($accountContextResolver);
        $request = new Request();

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([$account], $resolved);
    }

    public function testReturnsEmptyResultWhenRepositoryDoesNotReturnAnAccount(): void
    {
        $accountContextResolver = $this->createMock(AccountContextResolverInterface::class);
        $accountContextResolver->expects(self::once())
            ->method('resolveAccount')
            ->willReturn(null);
        $resolver = new AccountValueResolver($accountContextResolver);
        $request = new Request();

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    private function createArgumentMetadata(): ArgumentMetadata
    {
        return new ArgumentMetadata('account', AccountInterface::class, false, false, null);
    }
}

class AccountStub implements AccountInterface
{
    public function __construct(private readonly string $id, private ?string $name)
    {
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
