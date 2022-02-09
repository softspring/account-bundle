<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;

interface MultiAccountedInterface
{
    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection;

    public function addRelation(AccountUserRelationInterface $relation): void;

    public function removeRelation(AccountUserRelationInterface $relation): void;

    /**
     * @return AccountInterface[]|Collection
     */
    public function getAccounts(): Collection;

    public function removeAccount(AccountInterface $account): void;
}
