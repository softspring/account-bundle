<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Softspring\AccountBundle\Model\AccountInterface;

interface AccountManagerInterface
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
     * @return AccountInterface
     */
    public function create(): AccountInterface;

    /**
     * @param AccountInterface $account
     */
    public function save(AccountInterface $account): void;

    /**
     * @param AccountInterface $account
     */
    public function delete(AccountInterface $account): void;

    /**
     * @param array $criteria
     * @return AccountInterface|null
     */
    public function findAccountBy(array $criteria): ?AccountInterface;
}