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
     * @ORM\OneToMany(targetEntity="Softspring\AccountBundle\Model\AccountUserRelationInterface", mappedBy="user", cascade={"all"})
     */
    protected $accountRelations;

    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection
    {
        return $this->accountRelations;
    }

    /**
     * @param AccountUserRelationInterface $accountRelation
     */
    public function addRelation(AccountUserRelationInterface $accountRelation): void
    {
        if (!$this->accountRelations->contains($accountRelation)) {
            $this->accountRelations->add($accountRelation);
        }
    }

    /**
     * @param AccountUserRelationInterface $accountRelation
     */
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
        return $this->accountRelations->map(function(AccountUserRelationInterface $accountRelation) {
            return $accountRelation->getAccount();
        });
    }

    /**
     * @param AccountInterface $account
     */
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