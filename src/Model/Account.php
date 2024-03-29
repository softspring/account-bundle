<?php

namespace Softspring\AccountBundle\Model;

abstract class Account implements AccountInterface
{
    protected ?string $name = null;

    public function __toString(): string
    {
        return "{$this->getId()}";
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
