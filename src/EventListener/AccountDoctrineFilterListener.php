<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\AccountBundle\Context\AccountContextResolverInterface;
use Softspring\AccountBundle\Doctrine\Filter\AccountFilter;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AccountDoctrineFilterListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AccountContextResolverInterface $accountContextResolver,
    ) {
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

        if (!$this->accountContextResolver->hasAccountScope($request)) {
            return;
        }

        $account = $this->accountContextResolver->resolveAccount($request);

        if (!$account instanceof AccountInterface || null === $account->getId()) {
            return;
        }

        $this->em->getConfiguration()->addFilter('account', AccountFilter::class);
        $filter = $this->em->getFilters()->enable('account');
        $filter->setParameter('_account', (string) $account->getId());
    }
}
