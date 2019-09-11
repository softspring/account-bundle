<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Softspring\AccountBundle\Model\UserMultiAccountedInterface;
use Softspring\UserBundle\Model\ConfirmableInterface;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\PasswordRequestInterface;
use Softspring\UserBundle\Model\User as UserModel;
use Softspring\UserBundle\Entity\ConfirmableTrait;
use Softspring\UserBundle\Entity\NameSurnameTrait;
use Softspring\UserBundle\Entity\PasswordRequestTrait;

abstract class CompleteMultiAccountedUser extends UserModel implements NameSurnameInterface, PasswordRequestInterface, ConfirmableInterface, UserMultiAccountedInterface
{
    use NameSurnameTrait;
    use ConfirmableTrait;
    use PasswordRequestTrait;
    use UserMultiAccountedTrait;

    /**
     * CompleteMultiAccountedUser constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->accountRelations = new ArrayCollection();
    }
}