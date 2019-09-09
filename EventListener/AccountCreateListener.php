<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\Account\Manager\RelationManagerInterface;
use Softspring\Account\Model\AccountInterface;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\User\Model\OwnerInterface;
use Softspring\User\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccountCreateListener implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var RelationManagerInterface
     */
    protected $relationManager;

    /**
     * AccountCreateListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param RelationManagerInterface $relationManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, RelationManagerInterface $relationManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->relationManager = $relationManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsAccountEvents::REGISTER_FORM_VALID => ['onRegisterValidAddUser', 0],
            SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_FORM_VALID => ['onRegisterValidAddUser', 0],
        ];
    }

    public function onRegisterValidAddUser(GetResponseFormEvent $event)
    {
        /** @var AccountInterface $account */
        $account = $event->getForm()->getData();
        $user = $this->getUser();

        if ($user instanceof UserInterface) {
            if ($account instanceof OwnerInterface) {
                $account->setOwner($user);
            }

            if ($account instanceof MultiAccountedAccountInterface) {
                $account->addRelation($relation = $this->relationManager->create());

                $relation->setAccount($account);
                $relation->setUser($user);

                if (method_exists($relation, 'setRoles') && method_exists($relation, 'getRoles')) {
                    $relation->setRoles(array_unique(array_merge(['ROLE_OWNER'], $relation->getRoles())));
                }

                if (method_exists($relation, 'setGrantedBy')) {
                    $relation->setGrantedBy($user);
                }
            }
        }
    }

    protected function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }
}