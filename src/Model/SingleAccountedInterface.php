<?php

namespace Softspring\AccountBundle\Model;

interface SingleAccountedInterface
{
    public function getAccount(): ?AccountInterface;

    public function setAccount(?AccountInterface $account): void;
}
