<?php

namespace Softspring\AccountBundle\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Softspring\AccountBundle\Model\AccountFilterInterface;
use Softspring\AccountBundle\Model\AccountInterface;

class AccountFilter extends SQLFilter
{
    // values for this request
    public static string $FIELD_NAME = 'account_id';
    public static string $PARAMETER_NAME = '_account';

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (!$targetEntity->reflClass->implementsInterface(AccountFilterInterface::class)) {
            return '';
        }

        $value = $this->getParameter(self::$PARAMETER_NAME);

        $fieldName = self::$FIELD_NAME;

        // search for the first association mapping that implements AccountInterface
        foreach ($targetEntity->getAssociationMappings() as $mapping) {
            $targetEntityRef = new \ReflectionClass($mapping->targetEntity);
            if ($targetEntityRef->implementsInterface(AccountInterface::class)) {
                if (sizeof($mapping->joinColumnFieldNames) === 1) {
                    $fieldName = current($mapping->joinColumnFieldNames);
                }
                break;
            }
        }

        return "$targetTableAlias.$fieldName = $value";
    }
}
