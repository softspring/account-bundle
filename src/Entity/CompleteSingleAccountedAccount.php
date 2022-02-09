<?php

namespace Softspring\AccountBundle\Entity;

use Softspring\AccountBundle\Model\Account as AccountModel;
use Softspring\AccountBundle\Model\SingleAccountedAccountInterface;
use Softspring\UserBundle\Entity\OwnerTrait;

abstract class CompleteSingleAccountedAccount extends AccountModel implements SingleAccountedAccountInterface
{
    use OwnerTrait;
}
