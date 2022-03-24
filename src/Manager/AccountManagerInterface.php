<?php

namespace Softspring\AccountBundle\Manager;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\CrudlBundle\Manager\CrudlEntityManagerInterface;

interface AccountManagerInterface extends CrudlEntityManagerInterface
{
    /**
     * @return AccountInterface
     */
    public function createEntity(): object;

    /**
     * @param AccountInterface $entity
     */
    public function saveEntity(object $entity): void;

    /**
     * @param AccountInterface $entity
     */
    public function deleteEntity(object $entity): void;
}
