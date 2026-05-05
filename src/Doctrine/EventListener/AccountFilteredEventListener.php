<?php

namespace Softspring\AccountBundle\Doctrine\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Softspring\AccountBundle\Context\AccountContextResolverInterface;
use Softspring\AccountBundle\Model\AccountScopedInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AccountFilteredEventListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly AccountContextResolverInterface $accountContextResolver,
    ) {
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof AccountScopedInterface) {
            return;
        }

        if ($entity->getAccount() instanceof AccountInterface) {
            return;
        }

        $entity->setAccount($this->getAccount());
    }

    private function getAccount(): ?AccountInterface
    {
        if (!($request = $this->requestStack->getCurrentRequest()) instanceof Request) {
            return null;
        }

        return $this->accountContextResolver->resolveAccount($request);
    }
}
