<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Model;

interface AccountOwnedInterface
{
    public function getAccount(): ?AccountInterface;

    public function setAccount(?AccountInterface $account): void;
}
