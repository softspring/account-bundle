<?php

namespace Softspring\AccountBundle\Model;

use Softspring\UserBundle\Model\UserInterface;

abstract class AccountUserRelation implements AccountUserRelationInterface
{
    /**
     * @var AccountInterface|null
     */
    protected $account;

    /**
     * @var UserInterface|null
     */
    protected $user;

    /**
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    /**
     * @param AccountInterface|null $account
     */
    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface|null $user
     */
    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }
}