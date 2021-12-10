<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SlugIdTrait
{
    /**
     * @var string|null
     * @ORM\Id()
     * @ORM\Column(name="id", type="string", length=15, nullable=false, options={"fixed":true})
     */
    protected $id;

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return "{$this->getId()}";
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }
}