<?php

namespace Softspring\AccountBundle\Entity;

use Softspring\Account\Model\UserSingleAccountedInterface;
use Softspring\User\Model\ConfirmableInterface;
use Softspring\User\Model\NameSurnameInterface;
use Softspring\User\Model\PasswordRequestInterface;
use Softspring\User\Model\User as UserModel;
use Softspring\UserBundle\Entity\ConfirmableTrait;
use Softspring\UserBundle\Entity\NameSurnameTrait;
use Softspring\UserBundle\Entity\PasswordRequestTrait;

abstract class CompleteSingleAccountedUser extends UserModel implements NameSurnameInterface, PasswordRequestInterface, ConfirmableInterface, UserSingleAccountedInterface
{
    use NameSurnameTrait;
    use ConfirmableTrait;
    use PasswordRequestTrait;
    use AccountTrait;
}