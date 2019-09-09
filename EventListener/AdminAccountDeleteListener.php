<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\Account\Model\AccountUserRelation;
use Softspring\AccountBundle\Event\GetResponseFormEvent;
use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\User\Manager\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminAccountDeleteListener implements EventSubscriberInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * AdminAccountDeleteListener constructor.
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_FORM_VALID => ['onDeleteRemoveUsers', 0],
        ];
    }

    public function onDeleteRemoveUsers(GetResponseFormEvent $event)
    {
        $form = $event->getForm();
        $account = $form->getData();

        if (!$form->has('deleteSingleAccountedUsers')) {
            return;
        }

        $users = $form->get('deleteSingleAccountedUsers')->getData();

        foreach ($users as $user) {
            $account->removeUser($user);
            $this->userManager->delete($user);
        }
    }
}