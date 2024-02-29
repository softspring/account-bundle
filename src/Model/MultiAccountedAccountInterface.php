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
     * @return Collection<int, AccountUserRelationInterface>
     */
    public function getRelations(): Collection;

    public function addRelation(AccountUserRelationInterface $relation): void;

    public function removeRelation(AccountUserRelationInterface $relation): void;

    /**
     * @return Collection<int, UserInterface>
     */
    public function getUsers(): Collection;

    public function removeUser(UserInterface $user): void;
}
