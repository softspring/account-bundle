<?php

namespace Softspring\AccountBundle\Event;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class AccountEvent extends Event
{
    protected AccountInterface $account;

    protected ?Request $request;

    public function __construct(AccountInterface $account, ?Request $request)
    {
        $this->account = $account;
        $this->request = $request;
    }

    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }
}
