<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountMembershipInterface;
use Softspring\UserBundle\Model\UserInterface;

trait AccountMembershipsTrait
{
    /**
     * @var Collection<AccountMembershipInterface>
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: AccountMembershipInterface::class, cascade: ['all'])]
    protected Collection $memberships;

    /**
     * @return Collection<AccountMembershipInterface>
     */
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
        if ($this->memberships->contains($membership)) {
            $this->memberships->removeElement($membership);
        }
    }

    public function getUsers(): Collection
    {
        return $this->memberships->map(function (AccountMembershipInterface $membership): ?UserInterface {
            return $membership->getUser();
        });
    }

    public function removeUser(UserInterface $user): void
    {
        $memberships = $this->getMemberships()->filter(function (AccountMembershipInterface $membership) use ($user): bool {
            return $membership->getUser() === $user;
        });

        foreach ($memberships as $membership) {
            $this->removeMembership($membership);
        }

        if ($this->getOwner() === $user) {
            $this->setOwner(null);
        }
    }
}
