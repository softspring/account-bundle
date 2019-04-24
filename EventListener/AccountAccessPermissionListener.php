<?php

namespace Softspring\AccountBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Account\Model\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccountAccessPermissionListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $accountRouteParamName;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * AccountAccessPermissionListener constructor.
     * @param string $accountRouteParamName
     * @param AuthorizationCheckerInterface $authorizationChecker
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
     * @param GetResponseEvent $event
     * @throws NotFoundHttpException
     */
    public function onRequestCheckAccountAccessPermission(GetResponseEvent $event)
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