<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\UserBundle\Model\UserInterface;

interface AccountMembershipsInterface
{
    /**
     * @return Collection<AccountMembershipInterface>
     */
    public function getMemberships(): Collection;

    public function addMembership(AccountMembershipInterface $membership): void;

    public function removeMembership(AccountMembershipInterface $membership): void;

    /**
     * @return Collection<UserInterface>
     */
    public function getUsers(): Collection;

    public function removeUser(UserInterface $user): void;
}
