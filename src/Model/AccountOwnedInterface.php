<?php

namespace Softspring\AccountBundle\Model;

interface AccountOwnedInterface
{
    public function getAccount(): ?AccountInterface;

    public function setAccount(?AccountInterface $account): void;
}
