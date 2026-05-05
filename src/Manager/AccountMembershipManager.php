<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountMembershipInterface;

class AccountMembershipManager implements AccountMembershipManagerInterface
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getClass(): string
    {
        $metadata = $this->em->getClassMetadata(AccountMembershipInterface::class);

        return $metadata->getName();
    }

    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository(AccountMembershipInterface::class);
    }

    public function create(): AccountMembershipInterface
    {
        $className = $this->getClass();

        return new $className();
    }
}
