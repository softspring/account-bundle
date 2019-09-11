<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\UserBundle\Model\UserInterface;

interface MultiAccountedInterface
{
    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection;

    /**
     * @param AccountUserRelationInterface $relation
     */
    public function addRelation(AccountUserRelationInterface $relation): void;

    /**
     * @param AccountUserRelationInterface $relation
     */
    public function removeRelation(AccountUserRelationInterface $relation): void;

    /**
     * @return AccountInterface[]|Collection
     */
    public function getAccounts(): Collection;

    /**
     * @param AccountInterface $account
     */
    public function removeAccount(AccountInterface $account): void;
}