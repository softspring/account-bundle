<?php

namespace Softspring\AccountBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\CoreBundle\Twig\ExtensibleAppVariable;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
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
     * @var AppVariable
     */
    protected $twigAppVariable;

    /**
     * @var string
     */
    protected $findParamName;

    /**
     * AccountRequestListener constructor.
     *
     * @param EntityManagerInterface $em
     * @param string                 $accountRouteParamName
     * @param RouterInterface        $router
     * @param AppVariable            $twigAppVariable
     * @param string                 $findParamName
     *
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $em, string $accountRouteParamName, RouterInterface $router, AppVariable $twigAppVariable, string $findParamName)
    {
        $this->em = $em;
        $this->accountRouteParamName = $accountRouteParamName;
        $this->router = $router;
        $this->twigAppVariable = $twigAppVariable;
        $this->findParamName = $findParamName;

        if (!$this->twigAppVariable instanceof ExtensibleAppVariable) {
            throw new \Exception('You must configure SfsCoreBundle to extend twig app variable');
        }
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
     * @param GetResponseEvent|RequestEvent $event
     * @throws UnauthorizedHttpException
     */
    public function onRequestGetAccount($event)
    {
        $request = $event->getRequest();

        if ($request->attributes->has($this->accountRouteParamName)) {
            $account = $request->attributes->get($this->accountRouteParamName);

            if (!$account) {
                // hide not found with an unauthorized response
                throw new UnauthorizedHttpException('', 'Empty _account');
            }

            $account = $this->em->getRepository(AccountInterface::class)->findOneBy([$this->findParamName => $account]);

            if (!$account) {
                // hide not found with an unauthorized response
                throw new UnauthorizedHttpException('', 'Account not found');
            }

            $request->attributes->set($this->accountRouteParamName, $account);

            $context = $this->router->getContext();
            $context->setParameter('_account', $account);

            $this->twigAppVariable->setAccount($account);
        }
    }
}