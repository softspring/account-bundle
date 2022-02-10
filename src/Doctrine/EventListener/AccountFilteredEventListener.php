<?php

namespace Softspring\AccountBundle\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs as DeprecatedLifecycleEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Softspring\AccountBundle\Model\AccountFilterInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AccountFilteredEventListener implements EventSubscriber
{
    protected RequestStack $requestStack;

    /**
     * AccountFilteredEventListener constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    /**
     * @param DeprecatedLifecycleEventArgs|LifecycleEventArgs $eventArgs
     */
    public function prePersist($eventArgs)
    {
        $entity = $eventArgs->getObject();

        if (!$entity instanceof AccountFilterInterface) {
            return;
        }

        if ($entity->getAccount()) {
            return;
        }

        $entity->setAccount($this->getAccount());
    }

    private function getAccount(): ?AccountInterface
    {
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return null;
        }

        return $request->attributes->get('_account', null);
    }
}
