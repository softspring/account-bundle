<?php

namespace Softspring\AccountBundle\Templating;

use Softspring\Account\Model\AccountInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables as BaseGlobalVariables;

class GlobalVariables extends BaseGlobalVariables
{
    public function getAccount(): ?AccountInterface
    {
        return $this->getRequest()->attributes->get('_account', null);
    }
}