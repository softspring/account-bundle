<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\UserBundle\Model\UserInterface;

/**
 * @deprecated
 */
interface MultiAccountedAccountInterface
{
    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection;

    public function addRelation(AccountUserRelationInterface $relation): void;

    public function removeRelation(AccountUserRelationInterface $relation): void;

    /**
     * @return UserInterface[]|Collection
     */
    public function getUsers(): Collection;

    public function removeUser(UserInterface $user): void;
}
