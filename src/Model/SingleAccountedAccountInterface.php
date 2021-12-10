<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\UserBundle\Model\UserInterface;

interface SingleAccountedAccountInterface
{
    /**
     * @return UserInterface[]|Collection
     */
    public function getUsers(): Collection;

    /**
     * @param UserInterface $user
     */
    public function addUser(UserInterface $user): void;

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user): void;
}