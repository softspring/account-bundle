<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SlugIdTrait
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="string", length=15, nullable=false, options={"fixed":true})
     */
    protected ?string $id = null;

    public function __toString(): string
    {
        return "{$this->getId()}";
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }
}
