<?php

namespace Softspring\AccountBundle\Entity;

use Softspring\AccountBundle\Model\UserSingleAccountedInterface;
use Softspring\UserBundle\Entity\ConfirmableTrait;
use Softspring\UserBundle\Entity\NameSurnameTrait;
use Softspring\UserBundle\Entity\PasswordRequestTrait;
use Softspring\UserBundle\Model\ConfirmableInterface;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\PasswordRequestInterface;
use Softspring\UserBundle\Model\User as UserModel;

abstract class CompleteSingleAccountedUser extends UserModel implements NameSurnameInterface, PasswordRequestInterface, ConfirmableInterface, UserSingleAccountedInterface
{
    use NameSurnameTrait;
    use ConfirmableTrait;
    use PasswordRequestTrait;
    use AccountTrait;
}
