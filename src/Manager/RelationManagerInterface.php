<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;

interface RelationManagerInterface
{
    public function getClass(): string;

    /**
     * @return EntityRepository<AccountUserRelationInterface>
     */
    public function getRepository(): EntityRepository;

    public function create(): AccountUserRelationInterface;
}
