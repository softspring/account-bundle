<?php

namespace Softspring\AccountBundle\Tests\Security\Authorization\Voter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Model\AccountMembershipsInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipInterface;
use Softspring\AccountBundle\Security\Authorization\Voter\AccountAccessVoter;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\RolesAdminInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

class AccountAccessVoterTest extends TestCase
{
    public function testItAbstainsForUnsupportedAttributes(): void
    {
        $voter = new AccountAccessVoter();
        $token = $this->createToken(new UserStub('user-1'));

        $result = $voter->vote($token, new UsersAccountStub(), ['UNSUPPORTED']);

        self::assertSame(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    public function testItThrowsForInvalidUsers(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $voter = new AccountAccessVoter();
        $token = $this->createToken(new LegacyUserStub());

        $voter->vote($token, new UsersAccountStub(), ['CHECK_ACCOUNT_ACCESS']);
    }

    public function testItGrantsAccessToAdminUsers(): void
    {
        $voter = new AccountAccessVoter();
        $token = $this->createToken(new UserStub('admin', true));

        $result = $voter->vote($token, new UsersAccountStub(), ['CHECK_ACCOUNT_ACCESS']);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testItGrantsAccessToAccountOwners(): void
    {
        $owner = new UserStub('owner');
        $voter = new AccountAccessVoter();
        $token = $this->createToken($owner);

        $result = $voter->vote($token, new OwnerAccountStub($owner), ['CHECK_ACCOUNT_ACCESS']);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testItGrantsAccessToUsersLinkedToTheAccount(): void
    {
        $member = new UserStub('member');
        $account = new UsersAccountStub([$member]);
        $voter = new AccountAccessVoter();
        $token = $this->createToken($member);

        $result = $voter->vote($token, $account, ['CHECK_ACCOUNT_ACCESS']);

        self::assertSame(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testItDeniesAccessToUnrelatedUsers(): void
    {
        $voter = new AccountAccessVoter();
        $token = $this->createToken(new UserStub('outsider'));

        $result = $voter->vote($token, new UsersAccountStub(), ['CHECK_ACCOUNT_ACCESS']);

        self::assertSame(VoterInterface::ACCESS_DENIED, $result);
    }

    private function createToken(mixed $user): TokenInterface
    {
        return new TokenStub($user);
    }
}

class UserStub implements UserInterface, RolesAdminInterface
{
    private array $roles = [];

    public function __construct(
        private readonly string $identifier,
        private bool $admin = false,
        private bool $superAdmin = false,
    ) {
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function getDisplayName(): string
    {
        return $this->identifier;
    }

    public function __serialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'roles' => $this->roles,
            'admin' => $this->admin,
            'super_admin' => $this->superAdmin,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->roles = $data['roles'] ?? [];
        $this->admin = $data['admin'] ?? false;
        $this->superAdmin = $data['super_admin'] ?? false;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    public function isSuperAdmin(): bool
    {
        return $this->superAdmin;
    }

    public function setSuperAdmin(bool $superAdmin): void
    {
        $this->superAdmin = $superAdmin;
    }
}

class OwnerAccountStub implements AccountInterface, OwnerInterface
{
    public function __construct(private ?UserInterface $owner)
    {
    }

    public function getId(): ?string
    {
        return 'owner-account';
    }

    public function getName(): ?string
    {
        return 'Owner account';
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

class UsersAccountStub implements AccountInterface, AccountMembershipsInterface
{
    /**
     * @var Collection<int, AccountMembershipInterface>
     */
    private Collection $memberships;

    /**
     * @param UserInterface[] $users
     */
    public function __construct(array $users = [])
    {
        $this->memberships = new ArrayCollection(array_map(
            fn (UserInterface $user) => new AccountMembershipStub($this, $user),
            $users,
        ));
    }

    public function getId(): ?string
    {
        return 'users-account';
    }

    public function getName(): ?string
    {
        return 'Users account';
    }

    public function setName(?string $name): void
    {
    }

    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(AccountMembershipInterface $membership): void
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships->add($membership);
        }
    }

    public function removeMembership(AccountMembershipInterface $membership): void
    {
        $this->memberships->removeElement($membership);
    }

    public function getUsers(): Collection
    {
        return $this->memberships->map(fn (AccountMembershipInterface $membership) => $membership->getUser());
    }

    public function removeUser(UserInterface $user): void
    {
        foreach ($this->memberships as $membership) {
            if ($membership->getUser() === $user) {
                $this->memberships->removeElement($membership);
            }
        }
    }
}

class AccountMembershipStub implements AccountMembershipInterface
{
    public function __construct(
        private ?AccountInterface $account,
        private ?UserInterface $user,
        private array $roles = [],
    ) {
    }

    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}

class LegacyUserStub implements SymfonyUserInterface
{
    public function getRoles(): array
    {
        return [];
    }

    public function getUserIdentifier(): string
    {
        return 'legacy-user';
    }

    public function eraseCredentials(): void
    {
    }
}

class TokenStub implements TokenInterface
{
    public function __construct(private ?SymfonyUserInterface $user)
    {
    }

    public function __toString(): string
    {
        return '';
    }

    public function getUserIdentifier(): string
    {
        return $this->user?->getUserIdentifier() ?? '';
    }

    public function getRoleNames(): array
    {
        return $this->user?->getRoles() ?? [];
    }

    public function getUser(): ?SymfonyUserInterface
    {
        return $this->user;
    }

    public function setUser(SymfonyUserInterface $user): void
    {
        $this->user = $user;
    }

    public function eraseCredentials(): void
    {
    }

    public function getAttributes(): array
    {
        return [];
    }

    public function setAttributes(array $attributes): void
    {
    }

    public function hasAttribute(string $name): bool
    {
        return false;
    }

    public function getAttribute(string $name): mixed
    {
        return null;
    }

    public function setAttribute(string $name, mixed $value): void
    {
    }

    public function __serialize(): array
    {
        return [];
    }

    public function __unserialize(array $data): void
    {
    }
}
