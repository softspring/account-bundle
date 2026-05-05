<?php

namespace Softspring\AccountBundle\EventListener;

use Exception;
use Softspring\AccountBundle\Context\AccountContextResolverInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\TwigExtraBundle\Twig\ExtensibleAppVariable;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class AccountRequestListener implements EventSubscriberInterface
{
    /**
     * @throws Exception
     */
    public function __construct(
        private readonly AccountContextResolverInterface $accountContextResolver,
        private readonly RouterInterface $router,
        private readonly AppVariable $twigAppVariable,
        private readonly string $twigAppVariableName,
    ) {
        if (!$this->twigAppVariable instanceof ExtensibleAppVariable) {
            throw new Exception('You must configure SfsTwigExtraBundle to extend twig app variable');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onRequestGetAccount', 30], // router listener has 32
            ],
        ];
    }

    /**
     * @throws UnauthorizedHttpException
     */
    public function onRequestGetAccount(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->accountContextResolver->hasAccountScope($request)) {
            return;
        }

        $account = $this->accountContextResolver->resolveAccount($request);

        if (!$account instanceof AccountInterface) {
            // hide not found with an unauthorized response
            throw new UnauthorizedHttpException('', sprintf('Account not found for "%s".', $this->accountContextResolver->getAccountRouteParamName()));
        }

        $context = $this->router->getContext();
        $context->setParameter($this->accountContextResolver->getAccountRouteParamName(), $account);

        call_user_func([$this->twigAppVariable, 'set'.ucfirst($this->twigAppVariableName)], $account);
    }
}
