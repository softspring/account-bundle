<?php

namespace Softspring\AccountBundle\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Softspring\Account\Model\AccountFilterInterface;

class AccountFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (!$targetEntity->reflClass->implementsInterface(AccountFilterInterface::class)) {
            return '';
        }

        return $targetTableAlias.'.account_id = ' . $this->getParameter('_account');
    }
}