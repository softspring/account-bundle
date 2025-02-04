<?php

namespace Softspring\AccountBundle\Model;

interface AccountRelatedInterface
{
    public function getAccount(): ?AccountInterface;

    public function setAccount(?AccountInterface $account): void;
}
