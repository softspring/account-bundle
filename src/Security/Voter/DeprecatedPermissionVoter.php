<?php

namespace Softspring\AccountBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @deprecated since 5.1, will be removed in 6.0
 */
class DeprecatedPermissionVoter implements VoterInterface
{
    const DEPRECATIONS = [
        'ROLE_ADMIN_ACCOUNTS_LIST' => 'PERMISSION_SFS_ACCOUNT_ADMIN_ACCOUNTS_LIST',
        'ROLE_ADMIN_ACCOUNTS_DETAILS' => 'PERMISSION_SFS_ACCOUNT_ADMIN_ACCOUNTS_DETAILS',
        'ROLE_ADMIN_ACCOUNTS_UPDATE' => 'PERMISSION_SFS_ACCOUNT_ADMIN_ACCOUNTS_UPDATE',
        'ROLE_ADMIN_ACCOUNTS_DELETE' => 'PERMISSION_SFS_ACCOUNT_ADMIN_ACCOUNTS_DELETE',
        'ROLE_ADMIN_ACCOUNTS_RO' => 'ROLE_SFS_ACCOUNT_ADMIN_ACCOUNTS_RO',
        'ROLE_ADMIN_ACCOUNTS_RW' => 'ROLE_SFS_ACCOUNT_ADMIN_ACCOUNTS_RW',
    ];

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (isset(self::DEPRECATIONS[$attributes[0]??''])) {
            trigger_deprecation('softspring/account-bundle', '5.1', sprintf('The role "%s" is deprecated, use "%s" instead. Will be removed in 6.0', $attributes[0], self::DEPRECATIONS[$attributes[0]]));
        }

        return self::ACCESS_ABSTAIN;
    }
}