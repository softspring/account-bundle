<?php

namespace Softspring\AccountBundle\Event;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class AccountEvent extends Event
{
    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * @var Request|null
     */
    protected $request;

    /**
     * AccountEvent constructor.
     *
     * @param AccountInterface $account
     * @param Request|null  $request
     */
    public function __construct(AccountInterface $account, ?Request $request)
    {
        $this->account = $account;
        $this->request = $request;
    }

    /**
     * @return AccountInterface
     */
    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }
}
