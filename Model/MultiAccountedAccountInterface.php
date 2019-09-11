<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\UserBundle\Model\UserInterface;

interface MultiAccountedAccountInterface
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
     * @return UserInterface[]|Collection
     */
    public function getUsers(): Collection;

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user): void;
}