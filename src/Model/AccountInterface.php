<?php

namespace Softspring\AccountBundle\Model;

interface AccountInterface
{
    /**
     * @return mixed|null
     */
    public function getId();

    public function getName(): ?string;

    public function setName(?string $name): void;
}
