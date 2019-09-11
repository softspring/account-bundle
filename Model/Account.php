<?php

namespace Softspring\AccountBundle\Model;

abstract class Account implements AccountInterface
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}