<?php

namespace Softspring\AccountBundle\Request;

use Softspring\AccountBundle\Context\AccountContextResolverInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AccountValueResolver implements ValueResolverInterface
{
    public function __construct(
        protected AccountContextResolverInterface $accountContextResolver,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (\is_object($request->attributes->get($argument->getName()))) {
            return [];
        }

        if (AccountInterface::class !== $argument->getType()) {
            return [];
        }

        $entity = $this->accountContextResolver->resolveAccount($request);

        if (!$entity instanceof AccountInterface) {
            return [];
        }

        return [$entity];
    }
}
