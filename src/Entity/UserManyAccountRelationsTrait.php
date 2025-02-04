<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;

trait UserManyAccountRelationsTrait
{
    /**
     * @var Collection<AccountUserRelationInterface>
     */
    #[ORM\OneToMany(targetEntity: AccountUserRelationInterface::class, mappedBy: 'user', cascade: ['all'])]
    protected Collection $accountRelations;

    /**
     * @return Collection<AccountUserRelationInterface>
     */
    public function getRelations(): Collection
    {
        return $this->accountRelations;
    }

    public function addRelation(AccountUserRelationInterface $userRelation): void
    {
        if (!$this->accountRelations->contains($userRelation)) {
            $this->accountRelations->add($userRelation);
        }
    }

    public function removeRelation(AccountUserRelationInterface $userRelation): void
    {
        if ($this->accountRelations->contains($userRelation)) {
            $this->accountRelations->removeElement($userRelation);
        }
    }

    public function getAccounts(): Collection
    {
        return $this->accountRelations->map(function (AccountUserRelationInterface $userRelation) {
            return $userRelation->getAccount();
        });
    }

    public function removeAccount(AccountInterface $account): void
    {
        $relations = $this->getRelations()->filter(function (AccountUserRelationInterface $relation) use ($account) {
            return $relation->getAccount() === $account;
        });

        foreach ($relations as $relation) {
            $this->removeRelation($relation);
        }
    }
}
