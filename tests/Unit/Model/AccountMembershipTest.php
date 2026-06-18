<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembership;
use Softspring\UserBundle\Model\UserInterface;

class AccountMembershipTest extends TestCase
{
    public function testItStoresAccountAndUserReferences(): void
    {
        $account = $this->createMock(AccountInterface::class);
        $user = $this->createMock(UserInterface::class);
        $membership = new TestAccountMembership();

        self::assertNull($membership->getAccount());
        self::assertNull($membership->getUser());

        $membership->setAccount($account);
        $membership->setUser($user);

        self::assertSame($account, $membership->getAccount());
        self::assertSame($user, $membership->getUser());
    }
}

class TestAccountMembership extends AccountMembership
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
