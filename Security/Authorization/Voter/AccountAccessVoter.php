<?php

namespace Softspring\AccountBundle\Security\Authorization\Voter;

use Softspring\Account\Model\AccountInterface;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\User\Model\OwnerInterface;
use Softspring\User\Model\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;

class AccountAccessVoter implements VoterInterface
{
    /**
     * @param mixed $account
     * @return bool
     */
    public function supportsObject($account)
    {
        if (!is_object($account)) {
            return false;
        }

        return $account instanceof AccountInterface;
    }

    /**
     * @param TokenInterface   $token
     * @param AccountInterface $account
     * @param array            $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $account, array $attributes)
    {
        if (($attributes[0]?? null) !== 'CHECK_ACCOUNT_ACCESS') {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (!$this->supportsObject($account)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (! $user instanceof UserInterface) {
            throw new InvalidArgumentException('Invalid user class');
        }

        if ( ! $this->checkUser($account, $user)) {
            return VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_GRANTED;
    }

    /**
     * @param AccountInterface $account
     * @param UserInterface    $user
     *
     * @return bool
     */
    protected function checkUser(AccountInterface $account, $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($account instanceof OwnerInterface) {
            if ($account->getOwner() === $user) {
                return true;
            }
        }

        if ($account instanceof MultiAccountedAccountInterface) {
            return $account->getUsers()->contains($user);
        }

        return false;
    }
}