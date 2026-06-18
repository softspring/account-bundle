<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Entity\SlugIdTrait;
use Softspring\AccountBundle\Model\Account;

class AccountTest extends TestCase
{
    public function testItStoresTheAccountName(): void
    {
        $account = new TestAccount();

        self::assertNull($account->getName());

        $account->setName('Main account');

        self::assertSame('Main account', $account->getName());
    }

    public function testItUsesTheIdentifierAsStringRepresentation(): void
    {
        $account = new TestAccount();
        $account->setId('account-42');

        self::assertSame('account-42', (string) $account);
    }
}

class TestAccount extends Account
{
    use SlugIdTrait;
}
