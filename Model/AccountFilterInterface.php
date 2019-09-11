<?php

namespace Softspring\AccountBundle\Model;

interface AccountFilterInterface
{
    /**
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface;
}