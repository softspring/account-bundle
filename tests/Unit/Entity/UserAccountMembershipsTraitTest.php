<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Entity\UserAccountMembershipsTrait;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipInterface;
use Softspring\AccountBundle\Model\UserAccountMembershipsInterface;

class UserAccountMembershipsTraitTest extends TestCase
{
    public function testItAddsAndRemovesAccountMembershipsOnlyOnce(): void
    {
        $user = new UserWithAccountMemberships();
        $membership = $this->createConfiguredMock(AccountMembershipInterface::class, [
            'getAccount' => $this->createMock(AccountInterface::class),
        ]);

        $user->addAccountMembership($membership);
        $user->addAccountMembership($membership);

        self::assertCount(1, $user->getAccountMemberships());

        $user->removeAccountMembership($membership);

        self::assertCount(0, $user->getAccountMemberships());
    }

    public function testItReturnsAccountsFromMemberships(): void
    {
        $firstAccount = $this->createMock(AccountInterface::class);
        $secondAccount = $this->createMock(AccountInterface::class);
        $user = new UserWithAccountMemberships([
            $this->createConfiguredMock(AccountMembershipInterface::class, ['getAccount' => $firstAccount]),
            $this->createConfiguredMock(AccountMembershipInterface::class, ['getAccount' => $secondAccount]),
        ]);

        self::assertSame([$firstAccount, $secondAccount], $user->getAccounts()->toArray());
    }

    public function testItRemovesMembershipsForTheGivenAccount(): void
    {
        $account = $this->createMock(AccountInterface::class);
        $otherAccount = $this->createMock(AccountInterface::class);
        $matchingMembership = $this->createConfiguredMock(AccountMembershipInterface::class, ['getAccount' => $account]);
        $otherMembership = $this->createConfiguredMock(AccountMembershipInterface::class, ['getAccount' => $otherAccount]);
        $user = new UserWithAccountMemberships([$matchingMembership, $otherMembership]);

        $user->removeAccount($account);

        self::assertSame([$otherMembership], array_values($user->getAccountMemberships()->toArray()));
    }
}

class UserWithAccountMemberships implements UserAccountMembershipsInterface
{
    use UserAccountMembershipsTrait;

    /**
     * @param AccountMembershipInterface[] $accountMemberships
     */
    public function __construct(array $accountMemberships = [])
    {
        $this->accountMemberships = new ArrayCollection($accountMemberships);
    }
}
