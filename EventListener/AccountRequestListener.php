<?php

namespace Softspring\AccountBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AccountRequestListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestGetAccount', 200],
                ['onRequestCheckAccountAccess', 0],
                ['onRequestEnableDoctrineAccountFilter', -200],
            ],
        ];
    }

    public function onRequestGetAccount(GetResponseEvent $event)
    {
        // TODO get account parameter
    }

    public function onRequestCheckAccountAccess(GetResponseEvent $event)
    {
        // TODO check access
    }

    public function onRequestEnableDoctrineAccountFilter(GetResponseEvent $event)
    {
//        $filter = $this->em
//            ->getFilters()
//            ->enable('fortune_cookie_discontinued');
//        $filter->setParameter('discontinued', false);
    }
}