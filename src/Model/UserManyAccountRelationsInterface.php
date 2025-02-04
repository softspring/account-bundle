<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;

interface UserManyAccountRelationsInterface
{
    /**
     * @return Collection<AccountUserRelationInterface>
     */
    public function getRelations(): Collection;

    public function addRelation(AccountUserRelationInterface $relation): void;

    public function removeRelation(AccountUserRelationInterface $relation): void;

    /**
     * @return Collection<AccountInterface>
     */
    public function getAccounts(): Collection;

    public function removeAccount(AccountInterface $account): void;
}
