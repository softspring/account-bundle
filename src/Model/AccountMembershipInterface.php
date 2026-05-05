<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Model;

use Softspring\UserBundle\Model\SingleUserInterface;

interface AccountMembershipInterface extends AccountOwnedInterface, SingleUserInterface
{
    public function getRoles(): array;

    public function setRoles(array $roles): void;
}
