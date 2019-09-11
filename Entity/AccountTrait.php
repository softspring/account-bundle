<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountInterface;

trait AccountTrait
{
    /**
     * @var AccountInterface|null
     * @ORM\ManyToOne(targetEntity="Softspring\AccountBundle\Model\AccountInterface", cascade={"all"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
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