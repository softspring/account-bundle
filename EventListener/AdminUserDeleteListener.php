<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\MultiAccountedInterface;
use Softspring\UserBundle\Event\GetResponseFormEvent;
use Softspring\UserBundle\SfsUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminUserDeleteListener implements EventSubscriberInterface
{
    /**
     * @var AccountManagerInterface
     */
    protected $accountManager;

    /**
     * AdminUserDeleteListener constructor.
     * @param AccountManagerInterface $accountManager
     */
    public function __construct(AccountManagerInterface $accountManager)
    {
        $this->accountManager = $accountManager;
    }


    public static function getSubscribedEvents()
    {
        return [
            SfsUserEvents::ADMIN_USERS_DELETE_FORM_VALID => ['onDeleteRemoveAccounts', 0],
        ];
    }

    public function onDeleteRemoveAccounts(GetResponseFormEvent $event)
    {
        $form = $event->getForm();
        /** @var MultiAccountedInterface $user */
        $user = $form->getData();

        if (!$form->has('deleteOwnedAccounts')) {
            return;
        }

        $accounts = $form->get('deleteOwnedAccounts')->getData();

        foreach ($accounts as $account) {
            $user->removeAccount($account);
            $this->accountManager->delete($account);
        }
    }
}