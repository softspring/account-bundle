<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\Account\Model\UserMultiAccountedInterface;
use Softspring\UserBundle\Event\GetResponseUserEvent;
use Softspring\UserBundle\SfsUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class UserRegisterListener implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * UserRegisterListener constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsUserEvents::REGISTER_SUCCESS => [
                ['onUserRegisterRedirectToAccount', 0]
            ],
            SfsUserEvents::INVITATION_ACCEPTED => [
                ['onUserRegisterRedirectToAccount', 0]
            ],
        ];
    }

    public function onUserRegisterRedirectToAccount(GetResponseUserEvent $event)
    {
        $user = $event->getUser();

        if ($user instanceof UserMultiAccountedInterface) {
            if (!$user->getAccounts()->count()) {
                $event->setResponse(new RedirectResponse($this->router->generate('sfs_account_register')));
            }
        }
    }
}