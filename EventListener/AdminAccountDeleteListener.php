<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\CrudlBundle\Event\GetResponseFormEvent;
use Softspring\UserBundle\Manager\UserManagerInterface;
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