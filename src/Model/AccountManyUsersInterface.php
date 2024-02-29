<?php

namespace Softspring\AccountBundle\Model;

use Doctrine\Common\Collections\Collection;
use Softspring\UserBundle\Model\UserInterface;

interface AccountManyUsersInterface
{
    /**
     * @return Collection<int, UserInterface>
     */
    public function getUsers(): Collection;

    public function addUser(UserInterface $user): void;

    public function removeUser(UserInterface $user): void;
}
