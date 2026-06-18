<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Event\AccountEvent;
use Softspring\AccountBundle\Event\GetResponseAccountEvent;
use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountEventTest extends TestCase
{
    public function testItExposesTheAccountAndRequest(): void
    {
        $account = $this->createMock(AccountInterface::class);
        $request = new Request();
        $event = new AccountEvent($account, $request);

        self::assertSame($account, $event->getAccount());
        self::assertSame($request, $event->getRequest());
    }

    public function testGetResponseAccountEventStoresAResponse(): void
    {
        $account = $this->createMock(AccountInterface::class);
        $event = new GetResponseAccountEvent($account, null);
        $response = new RedirectResponse('/accounts');

        self::assertNull($event->getResponse());

        $event->setResponse($response);

        self::assertSame($response, $event->getResponse());
    }
}
