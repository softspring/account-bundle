<?php

namespace Softspring\AccountBundle\Manager;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\CrudlBundle\Manager\DefaultCrudlEntityManager;

class AccountManager extends DefaultCrudlEntityManager implements AccountManagerInterface
{
    /**
     * @deprecated
     */
    public function create(): AccountInterface
    {
        return $this->createEntity();
    }

    /**
     * @deprecated
     */
    public function save(AccountInterface $account): void
    {
        $this->saveEntity($account);
    }

    /**
     * @deprecated
     */
    public function delete(AccountInterface $account): void
    {
        $this->deleteEntity($account);
    }

    /**
     * @deprecated Use $manager->getRepository()->findOneBy($criteria);
     */
    public function findAccountBy(array $criteria): ?AccountInterface
    {
        return $this->getRepository()->findOneBy($criteria);
    }
}