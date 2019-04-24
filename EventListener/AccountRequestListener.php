<?php

namespace Softspring\AccountBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Account\Model\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class AccountRequestListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $accountRouteParamName;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * AccountRequestListener constructor.
     * @param EntityManagerInterface $em
     * @param string $accountRouteParamName
     * @param RouterInterface $router
     */
    public function __construct(EntityManagerInterface $em, string $accountRouteParamName, RouterInterface $router)
    {
        $this->em = $em;
        $this->accountRouteParamName = $accountRouteParamName;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestGetAccount', 30], // router listener has 32
            ],
        ];
    }

    /**
     * @param GetResponseEvent $event
     * @throws UnauthorizedHttpException
     */
    public function onRequestGetAccount(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has($this->accountRouteParamName)) {
            $account = $request->attributes->get($this->accountRouteParamName);

            if (!$account) {
                // hide not found with an unauthorized response
                throw new UnauthorizedHttpException('', 'Empty _account');
            }

            $account = $this->em->getRepository(AccountInterface::class)->findOneById($account);

            if (!$account) {
                // hide not found with an unauthorized response
                throw new UnauthorizedHttpException('', 'Account not found');
            }

            $request->attributes->set($this->accountRouteParamName, $account);

            $context = $this->router->getContext();
            $context->setParameter('_account', $account);
        }
    }
}