<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Softspring\AccountBundle\Model\Account as AccountModel;
use Softspring\AccountBundle\Model\MultiAccountedAccountInterface;
use Softspring\UserBundle\Entity\OwnerTrait;

/**
 * @deprecated
 */
abstract class CompleteMultiAccountedAccount extends AccountModel implements MultiAccountedAccountInterface
{
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
