<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;

/**
 * @deprecated
 */
interface MultiAccountedInterface
{
    /**
     * @return Collection<int, AccountUserRelationInterface>
     */
    public function getRelations(): Collection;

    public function addRelation(AccountUserRelationInterface $relation): void;

    public function removeRelation(AccountUserRelationInterface $relation): void;

    /**
     * @return Collection<int, AccountInterface>
     */
    public function getAccounts(): Collection;

    public function removeAccount(AccountInterface $account): void;
}
