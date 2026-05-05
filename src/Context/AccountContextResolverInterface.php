<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Context;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;

interface AccountContextResolverInterface
{
    public function getAccountRouteParamName(): string;

    public function hasAccountScope(Request $request): bool;

    public function resolveAccount(Request $request): ?AccountInterface;
}
