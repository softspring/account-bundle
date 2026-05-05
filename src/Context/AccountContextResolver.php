<?php

namespace Softspring\AccountBundle\Context;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountContextResolver implements AccountContextResolverInterface
{
    public function __construct(
        private readonly AccountManagerInterface $accountManager,
        private readonly string $accountRouteParamName = '_account',
        private readonly string $findFieldName = 'id',
    ) {
    }

    public function getAccountRouteParamName(): string
    {
        return $this->accountRouteParamName;
    }

    public function hasAccountScope(Request $request): bool
    {
        return $request->attributes->has($this->accountRouteParamName);
    }

    public function resolveAccount(Request $request): ?AccountInterface
    {
        $account = $request->attributes->get($this->accountRouteParamName);

        if ($account instanceof AccountInterface) {
            return $account;
        }

        if (!is_scalar($account) || '' === trim((string) $account)) {
            return null;
        }

        $entity = $this->accountManager->getRepository()->findOneBy([
            $this->findFieldName => trim((string) $account),
        ]);

        if (!$entity instanceof AccountInterface) {
            return null;
        }

        $request->attributes->set($this->accountRouteParamName, $entity);

        return $entity;
    }
}
