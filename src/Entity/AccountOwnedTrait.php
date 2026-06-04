<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountInterface;

trait AccountOwnedTrait
{
    #[ORM\ManyToOne(targetEntity: AccountInterface::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
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
