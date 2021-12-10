<?php

namespace Softspring\AccountBundle\Model;

use Softspring\UserBundle\Model\OwnerInterface;

interface AccountInterface extends OwnerInterface
{
    /**
     * @return mixed|null
     */
    public function getId();

    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void;
}