<?php

namespace Softspring\AccountBundle\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Softspring\AccountBundle\Model\AccountFilterInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\SingleAccountedInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AccountFilteredEventListener implements EventSubscriber
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof AccountFilterInterface) {
            return;
        }

        if ($entity->getAccount()) {
            return;
        }

        if ($entity instanceof SingleAccountedInterface) {
            $entity->setAccount($this->getAccount());
        }
    }

    private function getAccount(): ?AccountInterface
    {
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return null;
        }

        return $request->attributes->get('_account', null);
    }
}
