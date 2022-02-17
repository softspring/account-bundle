<?php

namespace Softspring\AccountBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\AccountBundle\Doctrine\Filter\AccountFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AccountDoctrineFilterListener implements EventSubscriberInterface
{
    protected EntityManagerInterface $em;

    protected string $accountRouteParamName;

    public function __construct(EntityManagerInterface $em, string $accountRouteParamName)
    {
        $this->em = $em;
        $this->accountRouteParamName = $accountRouteParamName;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestEnableDoctrineAccountFilter', -200],
            ],
        ];
    }

    public function onRequestEnableDoctrineAccountFilter(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->attributes->has($this->accountRouteParamName)) {
            $this->em->getConfiguration()->addFilter('account', AccountFilter::class);
            $filter = $this->em->getFilters()->enable('account');
            $filter->setParameter('_account', $request->attributes->get($this->accountRouteParamName));
        }
    }
}
