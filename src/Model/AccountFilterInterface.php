<?php

namespace Softspring\AccountBundle\Model;

interface AccountFilterInterface
{
    public function getAccount(): ?AccountInterface;
}
