<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\AccountBundle\Model\UserMultiAccountedInterface;
use Softspring\UserBundle\Event\GetResponseUserEvent;
use Softspring\UserBundle\SfsUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class UserRegisterListener implements EventSubscriberInterface
{
    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsUserEvents::REGISTER_SUCCESS => [
                ['onUserRegisterRedirectToAccount', 0],
            ],
            SfsUserEvents::INVITATION_ACCEPTED => [
                ['onUserRegisterRedirectToAccount', 0],
            ],
        ];
    }

    public function onUserRegisterRedirectToAccount(GetResponseUserEvent $event): void
    {
        $user = $event->getUser();

        if ($user instanceof UserMultiAccountedInterface) {
            if (!$user->getAccounts()->count()) {
                $event->setResponse(new RedirectResponse($this->router->generate('sfs_account_register')));
            }
        }
    }
}
