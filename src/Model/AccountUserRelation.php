<?php

namespace Softspring\AccountBundle\Model;

use Softspring\UserBundle\Model\UserInterface;

abstract class AccountUserRelation implements AccountUserRelationInterface
{
    protected ?AccountInterface $account;

    protected ?UserInterface $user;

    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}
