<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Softspring\Account\Model\Account as AccountModel;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\UserBundle\Entity\OwnerTrait;

abstract class CompleteMultiAccountedAccount extends AccountModel implements MultiAccountedAccountInterface
{
    use SlugIdTrait;
    use OwnerTrait;
    use AccountMultiAccountedTrait;

    /**
     * CompleteMultiAccountedAccount constructor.
     */
    public function __construct()
    {
        $this->userRelations = new ArrayCollection();
    }
}