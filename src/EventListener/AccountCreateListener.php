<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\AccountBundle\Manager\AccountMembershipManagerInterface;
use Softspring\AccountBundle\Model\AccountMembershipsInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipInterface;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\Component\CrudlController\Event\GetResponseFormEvent;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AccountCreateListener implements EventSubscriberInterface
{
    protected TokenStorageInterface $tokenStorage;

    protected AccountMembershipManagerInterface $membershipManager;

    public function __construct(TokenStorageInterface $tokenStorage, AccountMembershipManagerInterface $membershipManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->membershipManager = $membershipManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsAccountEvents::REGISTER_FORM_VALID => ['onRegisterValidAddUser', 0],
            SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_FORM_VALID => ['onAccountCreationAddUser', 0],
        ];
    }

    public function onRegisterValidAddUser(GetResponseFormEvent $event): void
    {
        /** @var AccountInterface $account */
        $account = $event->getForm()->getData();
        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            if ($account instanceof OwnerInterface) {
                $account->setOwner($user);
            }

            if ($account instanceof AccountMembershipsInterface) {
                if ($account->getMemberships()->filter(function (AccountMembershipInterface $membership) use ($user): bool {
                    return $membership->getUser() === $user;
                })->count()) {
                    return;
                }

                $account->addMembership($membership = $this->membershipManager->create());
                if (method_exists($user, 'addAccountMembership')) {
                    $user->addAccountMembership($membership);
                }

                $membership->setAccount($account);
                $membership->setUser($user);

                if (method_exists($membership, 'setRoles') && method_exists($membership, 'getRoles')) {
                    $membership->setRoles(array_unique(array_merge(['ROLE_OWNER'], $membership->getRoles())));
                }

                if (method_exists($membership, 'setGrantedBy')) {
                    $membership->setGrantedBy($user);
                }
            }
        }
    }

    public function onAccountCreationAddUser(GetResponseFormEvent $event): void
    {
        /** @var AccountInterface $account */
        $account = $event->getForm()->getData();

        if (!$account instanceof OwnerInterface) {
            return;
        }

        $user = $account->getOwner();

        if ($user instanceof UserInterface && $account instanceof AccountMembershipsInterface) {
            $account->addMembership($membership = $this->membershipManager->create());
            if (method_exists($user, 'addAccountMembership')) {
                $user->addAccountMembership($membership);
            }
            $membership->setAccount($account);
            $membership->setUser($user);
            if (method_exists($membership, 'setRoles') && method_exists($membership, 'getRoles')) {
                $membership->setRoles(array_unique(array_merge(['ROLE_OWNER'], $membership->getRoles())));
            }
            if (method_exists($membership, 'setGrantedBy')) {
                $membership->setGrantedBy($user);
            }
        }
    }

    protected function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof TokenInterface) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }
}
