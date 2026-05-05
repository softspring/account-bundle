<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipInterface;

trait UserAccountMembershipsTrait
{
    /**
     * @var Collection<AccountMembershipInterface>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AccountMembershipInterface::class, cascade: ['all'])]
    protected Collection $accountMemberships;

    /**
     * @return Collection<AccountMembershipInterface>
     */
    public function getAccountMemberships(): Collection
    {
        return $this->accountMemberships;
    }

    public function addAccountMembership(AccountMembershipInterface $membership): void
    {
        if (!$this->accountMemberships->contains($membership)) {
            $this->accountMemberships->add($membership);
        }
    }

    public function removeAccountMembership(AccountMembershipInterface $membership): void
    {
        if ($this->accountMemberships->contains($membership)) {
            $this->accountMemberships->removeElement($membership);
        }
    }

    public function getAccounts(): Collection
    {
        return $this->accountMemberships->map(function (AccountMembershipInterface $membership): ?AccountInterface {
            return $membership->getAccount();
        });
    }

    public function removeAccount(AccountInterface $account): void
    {
        $memberships = $this->getAccountMemberships()->filter(function (AccountMembershipInterface $membership) use ($account): bool {
            return $membership->getAccount() === $account;
        });

        foreach ($memberships as $membership) {
            $this->removeAccountMembership($membership);
        }
    }
}
