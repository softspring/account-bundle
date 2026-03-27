<?php

namespace Softspring\AccountBundle\Tests\Request;

use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Request\AccountValueResolver;
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AccountValueResolverTest extends TestCase
{
    public function testSkipsArgumentsThatAreAlreadyResolved(): void
    {
        $manager = $this->createMock(AccountManagerInterface::class);
        $resolver = new AccountValueResolver($manager);
        $request = new Request();
        $request->attributes->set('account', new stdClass());

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testSkipsUnsupportedArgumentTypes(): void
    {
        $manager = $this->createMock(AccountManagerInterface::class);
        $resolver = new AccountValueResolver($manager);
        $request = new Request();
        $request->attributes->set('_account', 'account-42');

        $resolved = $resolver->resolve($request, new ArgumentMetadata('account', stdClass::class, false, false, null));

        self::assertSame([], $resolved);
    }

    public function testReturnsEmptyResultWhenRouteParameterIsMissing(): void
    {
        $manager = $this->createMock(AccountManagerInterface::class);
        $resolver = new AccountValueResolver($manager);

        $resolved = $resolver->resolve(new Request(), $this->createArgumentMetadata());

        self::assertSame([], $resolved);
    }

    public function testResolvesAccountUsingConfiguredRouteParameterAndFindField(): void
    {
        $account = new AccountStub('account-42', 'Main account');
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['slug' => 'main-account'])
            ->willReturn($account);

        $manager = $this->createMock(AccountManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new AccountValueResolver($manager, '_workspace', 'slug');
        $request = new Request();
        $request->attributes->set('_workspace', 'main-account');

        $resolved = $resolver->resolve($request, $this->createArgumentMetadata());

        self::assertSame([$account], $resolved);
    }

    public function testReturnsEmptyResultWhenRepositoryDoesNotReturnAnAccount(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['id' => 'account-42'])
            ->willReturn(new stdClass());

        $manager = $this->createMock(AccountManagerInterface::class);
        $manager->expects(self::once())
            ->method('getRepository')
            ->willReturn($repository);

        $resolver = new AccountValueResolver($manager);
        $request = new Request();
        $request->attributes->set('_account', 'account-42');

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
