<?php

namespace Softspring\AccountBundle\Model;

use Softspring\UserBundle\Model\OwnerInterface;

interface AccountInterface extends OwnerInterface
{
    /**
     * @return mixed|null
     */
    public function getId();

    public function getName(): ?string;

    public function setName(?string $name): void;
}
