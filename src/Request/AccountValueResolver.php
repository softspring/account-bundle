<?php

namespace Softspring\AccountBundle\Request;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class AccountValueResolver implements ValueResolverInterface
{
    public function __construct(
        protected AccountManagerInterface $manager,
        protected string $accountRouteParamName = '_account',
        protected string $findFieldName = 'id',
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

        $query = $request->attributes->get($this->accountRouteParamName);

        if (null === $query || '' === $query) {
            return [];
        }

        $entity = $this->manager->getRepository()->findOneBy([$this->findFieldName => $query]);

        if (!$entity instanceof AccountInterface) {
            return [];
        }

        return [$entity];
    }
}
