<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;

trait UserMultiAccountedTrait
{
    /**
     * @var AccountUserRelationInterface[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Softspring\AccountBundle\Model\AccountUserRelationInterface", mappedBy="user", cascade={"all"})
     */
    protected Collection $accountRelations;

    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection
    {
        return $this->accountRelations;
    }

    public function addRelation(AccountUserRelationInterface $accountRelation): void
    {
        if (!$this->accountRelations->contains($accountRelation)) {
            $this->accountRelations->add($accountRelation);
        }
    }

    public function removeRelation(AccountUserRelationInterface $accountRelation): void
    {
        if ($this->accountRelations->contains($accountRelation)) {
            $this->accountRelations->removeElement($accountRelation);
        }
    }

    /**
     * @return AccountInterface[]|Collection
     */
    public function getAccounts(): Collection
    {
        /** @var Collection $accounts */
        $accounts = $this->accountRelations->map(function (AccountUserRelationInterface $accountRelation) {
            return $accountRelation->getAccount();
        });

        return $accounts;
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
