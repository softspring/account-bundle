<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountMembershipInterface;

interface AccountMembershipManagerInterface
{
    public function getClass(): string;

    public function getRepository(): EntityRepository;

    public function create(): AccountMembershipInterface;
}
