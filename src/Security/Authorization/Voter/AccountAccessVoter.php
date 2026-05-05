<?php

namespace Softspring\AccountBundle\Security\Authorization\Voter;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipsInterface;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\RolesAdminInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class AccountAccessVoter implements VoterInterface
{
    public function supportsObject(mixed $account): bool
    {
        if (!is_object($account)) {
            return false;
        }

        return $account instanceof AccountInterface;
    }

    /**
     * @psalm-param AccountInterface $subject
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes, mixed $vote = null): int
    {
        $account = $subject;

        if (($attributes[0] ?? null) !== 'CHECK_ACCOUNT_ACCESS') {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (!$this->supportsObject($account)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if ('anon.' == $user) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (!$user instanceof UserInterface) {
            throw new InvalidArgumentException('Invalid user class');
        }

        if (!$this->checkUser($account, $user)) {
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_GRANTED;
    }

    /**
     * @param UserInterface $user
     */
    protected function checkUser(AccountInterface $account, $user): bool
    {
        if ($user instanceof RolesAdminInterface && $user->isAdmin()) {
            return true;
        }

        if ($account instanceof OwnerInterface && $account->getOwner() === $user) {
            return true;
        }

        if ($account instanceof AccountMembershipsInterface) {
            foreach ($account->getMemberships() as $membership) {
                if ($membership->getUser() === $user) {
                    return true;
                }
            }
        }

        return false;
    }
}
