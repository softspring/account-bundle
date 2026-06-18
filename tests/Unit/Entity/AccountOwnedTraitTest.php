<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Entity\AccountOwnedTrait;
use Softspring\AccountBundle\Model\AccountInterface;

class AccountOwnedTraitTest extends TestCase
{
    public function testItStoresTheOwningAccount(): void
    {
        $account = $this->createMock(AccountInterface::class);
        $entity = new AccountOwnedEntity();

        self::assertNull($entity->getAccount());

        $entity->setAccount($account);

        self::assertSame($account, $entity->getAccount());
    }
}

class AccountOwnedEntity
{
    use AccountOwnedTrait;
}
