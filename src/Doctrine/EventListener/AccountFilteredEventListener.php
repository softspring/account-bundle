<?php

namespace Softspring\AccountBundle\Doctrine\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Softspring\AccountBundle\Model\AccountFilterInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountRelatedInterface;
use Softspring\AccountBundle\Model\SingleAccountedInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class AccountFilteredEventListener
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function prePersist(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof AccountFilterInterface) {
            return;
        }

        if ($entity->getAccount() instanceof AccountInterface) {
            return;
        }

        if ($entity instanceof SingleAccountedInterface || $entity instanceof AccountRelatedInterface) {
            $entity->setAccount($this->getAccount());
        }
    }

    private function getAccount(): ?AccountInterface
    {
        if (!($request = $this->requestStack->getCurrentRequest()) instanceof Request) {
            return null;
        }

        return $request->attributes->get('_account');
    }
}
