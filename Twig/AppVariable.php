<?php

namespace Softspring\AccountBundle\Twig;

use Softspring\Account\Model\AccountInterface;
use Symfony\Bridge\Twig\AppVariable as BaseAppVariable;

class AppVariable extends BaseAppVariable
{
    /**
     * @var AccountInterface|null
     */
    protected $account;

    /**
     * @return AccountInterface|null
     */
    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    /**
     * @param AccountInterface|null $account
     */
    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }
}