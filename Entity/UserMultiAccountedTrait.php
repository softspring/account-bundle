<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\Account\Model\AccountInterface;
use Softspring\Account\Model\AccountUserRelationInterface;

trait UserMultiAccountedTrait
{
    /**
     * @var AccountUserRelationInterface[]|Collection
     * @ORM\OneToMany(targetEntity="Softspring\Account\Model\AccountUserRelationInterface", mappedBy="user", cascade={"all"})
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
}