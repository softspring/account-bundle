<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountInterface;

trait AccountTrait
{
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToOne(targetEntity: AccountInterface::class, cascade: ['persist'])]
    protected ?AccountInterface $account = null;

    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }
}
