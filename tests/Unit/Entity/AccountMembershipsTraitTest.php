<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Entity\AccountMembershipsTrait;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipInterface;
use Softspring\AccountBundle\Model\AccountMembershipsInterface;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\UserInterface;

class AccountMembershipsTraitTest extends TestCase
{
    public function testItAddsAndRemovesMembershipsOnlyOnce(): void
    {
        $account = new AccountWithMemberships();
        $membership = $this->createConfiguredMock(AccountMembershipInterface::class, [
            'getUser' => $this->createMock(UserInterface::class),
        ]);

        $account->addMembership($membership);
        $account->addMembership($membership);

        self::assertCount(1, $account->getMemberships());

        $account->removeMembership($membership);

        self::assertCount(0, $account->getMemberships());
    }

    public function testItReturnsUsersFromMemberships(): void
    {
        $firstUser = $this->createMock(UserInterface::class);
        $secondUser = $this->createMock(UserInterface::class);
        $account = new AccountWithMemberships([
            $this->createConfiguredMock(AccountMembershipInterface::class, ['getUser' => $firstUser]),
            $this->createConfiguredMock(AccountMembershipInterface::class, ['getUser' => $secondUser]),
        ]);

        self::assertSame([$firstUser, $secondUser], $account->getUsers()->toArray());
    }

    public function testItRemovesUserMembershipsAndClearsOwnerWhenNeeded(): void
    {
        $owner = $this->createMock(UserInterface::class);
        $otherUser = $this->createMock(UserInterface::class);
        $ownerMembership = $this->createConfiguredMock(AccountMembershipInterface::class, ['getUser' => $owner]);
        $otherMembership = $this->createConfiguredMock(AccountMembershipInterface::class, ['getUser' => $otherUser]);
        $account = new AccountWithMemberships([$ownerMembership, $otherMembership]);
        $account->setOwner($owner);

        $account->removeUser($owner);

        self::assertSame([$otherMembership], array_values($account->getMemberships()->toArray()));
        self::assertNull($account->getOwner());
    }
}

class AccountWithMemberships implements AccountInterface, AccountMembershipsInterface, OwnerInterface
{
    use AccountMembershipsTrait;

    private ?UserInterface $owner = null;

    /**
     * @param AccountMembershipInterface[] $memberships
     */
    public function __construct(array $memberships = [])
    {
        $this->memberships = new ArrayCollection($memberships);
    }

    public function getId(): ?string
    {
        return 'account';
    }

    public function getName(): ?string
    {
        return 'Account';
    }

    public function setName(?string $name): void
    {
    }

    public function getOwner(): ?UserInterface
    {
        return $this->owner;
    }

    public function setOwner(?UserInterface $owner): void
    {
        $this->owner = $owner;
    }
}
