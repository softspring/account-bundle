<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\AccountBundle\SfsAccountEvents;
use Softspring\Component\CrudlController\Event\FormPrepareEvent;
use Softspring\Component\CrudlController\Event\GetResponseEntityEvent;
use Softspring\Component\CrudlController\Event\GetResponseFormEvent;
use Softspring\UserBundle\Manager\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class AdminAccountListener implements EventSubscriberInterface
{
    protected UserManagerInterface $userManager;
    protected RouterInterface $router;

    public function __construct(UserManagerInterface $userManager, RouterInterface $router)
    {
        $this->userManager = $userManager;
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsAccountEvents::ADMIN_ACCOUNTS_CREATE_SUCCESS => ['onSuccessRedirectToDetails', 0],
            SfsAccountEvents::ADMIN_ACCOUNTS_UPDATE_SUCCESS => ['onSuccessRedirectToDetails', 0],
            SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_FORM_VALID => ['onDeleteRemoveUsers', 0],
            SfsAccountEvents::ADMIN_ACCOUNTS_DELETE_FORM_PREPARE => ['onDeleteFormPrepare', 0],
        ];
    }

    public function onSuccessRedirectToDetails(GetResponseEntityEvent $event): void
    {
        $event->setResponse(new RedirectResponse($this->router->generate('sfs_account_admin_accounts_details', ['account' => $event->getEntity()])));
    }

    public function onDeleteRemoveUsers(GetResponseFormEvent $event): void
    {
        $form = $event->getForm();
        $account = $form->getData();

        if (!$form->has('deleteSingleAccountedUsers')) {
            return;
        }

        $users = $form->get('deleteSingleAccountedUsers')->getData();

        foreach ($users as $user) {
            $account->removeUser($user);
            $this->userManager->deleteEntity($user);
        }
    }

    public function onDeleteFormPrepare(FormPrepareEvent $event): void
    {
        $event->setFormOptions([
            'account' => $event->getEntity(),
        ]);
    }
}
