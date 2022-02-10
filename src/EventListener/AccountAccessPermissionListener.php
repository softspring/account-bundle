<?php

namespace Softspring\AccountBundle\EventListener;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccountAccessPermissionListener implements EventSubscriberInterface
{
    protected string $accountRouteParamName;

    protected AuthorizationCheckerInterface $authorizationChecker;

    /**
     * AccountAccessPermissionListener constructor.
     */
    public function __construct(string $accountRouteParamName, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->accountRouteParamName = $accountRouteParamName;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestCheckAccountAccessPermission', 0], // firewall listener has 8
            ],
        ];
    }

    /**
     * @param GetResponseEvent|RequestEvent $event
     *
     * @throws NotFoundHttpException
     */
    public function onRequestCheckAccountAccessPermission($event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has($this->accountRouteParamName)) {
            /** @var AccountInterface $account */
            $account = $request->attributes->get($this->accountRouteParamName);

            if (!$this->authorizationChecker->isGranted('CHECK_ACCOUNT_ACCESS', $account)) {
                throw new UnauthorizedHttpException('You are not allowed to enter this account');
            }
        }
    }
}
