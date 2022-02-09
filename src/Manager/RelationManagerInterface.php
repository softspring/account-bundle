<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;

interface RelationManagerInterface
{
    public function getClass(): string;

    public function getRepository(): EntityRepository;

    public function create(): AccountUserRelationInterface;
}
