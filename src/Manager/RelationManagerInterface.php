<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;

interface RelationManagerInterface
{
    /**
     * @return string
     */
    public function getClass(): string;

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository;

    /**
     * @return AccountUserRelationInterface
     */
    public function create(): AccountUserRelationInterface;
}