<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;

interface UserAccountMembershipsInterface
{
    /**
     * @return Collection<AccountMembershipInterface>
     */
    public function getAccountMemberships(): Collection;

    public function addAccountMembership(AccountMembershipInterface $membership): void;

    public function removeAccountMembership(AccountMembershipInterface $membership): void;

    /**
     * @return Collection<AccountInterface>
     */
    public function getAccounts(): Collection;

    public function removeAccount(AccountInterface $account): void;
}
