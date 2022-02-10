<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;

class RelationManager implements RelationManagerInterface
{
    protected EntityManagerInterface $em;

    /**
     * RelationManager constructor.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClass(): string
    {
        $metadata = $this->em->getClassMetadata(AccountUserRelationInterface::class);

        return $metadata->getName();
    }

    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository(AccountUserRelationInterface::class);
    }

    /**
     * {@inheritdoc}
     */
    public function create(): AccountUserRelationInterface
    {
        $className = $this->getClass();

        return new $className();
    }
}
