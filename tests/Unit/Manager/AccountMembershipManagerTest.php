<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Manager\AccountMembershipManager;
use Softspring\AccountBundle\Model\AccountMembership;
use Softspring\AccountBundle\Model\AccountMembershipInterface;

class AccountMembershipManagerTest extends TestCase
{
    public function testItResolvesTheConfiguredMembershipClass(): void
    {
        $metadata = new ClassMetadata(TestManagedAccountMembership::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())
            ->method('getClassMetadata')
            ->with(AccountMembershipInterface::class)
            ->willReturn($metadata);

        $manager = new AccountMembershipManager($em);

        self::assertSame(TestManagedAccountMembership::class, $manager->getClass());
    }

    public function testItReturnsTheMembershipRepository(): void
    {
        $repository = $this->createMock(EntityRepository::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())
            ->method('getRepository')
            ->with(AccountMembershipInterface::class)
            ->willReturn($repository);

        $manager = new AccountMembershipManager($em);

        self::assertSame($repository, $manager->getRepository());
    }

    public function testItCreatesTheResolvedMembershipClass(): void
    {
        $metadata = new ClassMetadata(TestManagedAccountMembership::class);
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())
            ->method('getClassMetadata')
            ->with(AccountMembershipInterface::class)
            ->willReturn($metadata);

        $manager = new AccountMembershipManager($em);

        self::assertInstanceOf(TestManagedAccountMembership::class, $manager->create());
    }
}

class TestManagedAccountMembership extends AccountMembership
{
    private array $roles = [];

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
