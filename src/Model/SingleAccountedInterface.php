<?php

namespace Softspring\AccountBundle\Model;

/**
 * @deprecated
 */
interface SingleAccountedInterface
{
    public function getAccount(): ?AccountInterface;

    public function setAccount(?AccountInterface $account): void;
}
