<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;
use Softspring\UserBundle\Model\UserInterface;

trait AccountManyUserRelationsTrait
{
    /**
     * @var Collection<AccountUserRelationInterface>
     */
    #[ORM\OneToMany(mappedBy: 'account', targetEntity: AccountUserRelationInterface::class, cascade: ['all'])]
    protected Collection $userRelations;

    /**
     * @return Collection<AccountUserRelationInterface>
     */
    public function getRelations(): Collection
    {
        return $this->userRelations;
    }

    public function addRelation(AccountUserRelationInterface $userRelation): void
    {
        if (!$this->userRelations->contains($userRelation)) {
            $this->userRelations->add($userRelation);
        }
    }

    public function removeRelation(AccountUserRelationInterface $userRelation): void
    {
        if ($this->userRelations->contains($userRelation)) {
            $this->userRelations->removeElement($userRelation);
        }
    }

    public function getUsers(): Collection
    {
        return $this->userRelations->map(function (AccountUserRelationInterface $userRelation): ?UserInterface {
            return $userRelation->getUser();
        });
    }

    public function removeUser(UserInterface $user): void
    {
        $relations = $this->getRelations()->filter(function (AccountUserRelationInterface $relation) use ($user): bool {
            return $relation->getUser() === $user;
        });

        foreach ($relations as $relation) {
            $this->removeRelation($relation);
        }

        if ($this->getOwner() === $user) {
            $this->setOwner(null);
        }
    }
}
