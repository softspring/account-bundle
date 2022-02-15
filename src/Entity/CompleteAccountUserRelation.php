<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountUserRelation as AccountUserRelationModel;
use Softspring\DoctrineTemplates\Entity\Traits\CreatedAtTimestamp;
use Softspring\UserBundle\Model\UserInterface;

abstract class CompleteAccountUserRelation extends AccountUserRelationModel
{
    use CreatedAtTimestamp;

    /**
     * @ORM\ManyToOne(targetEntity="Softspring\UserBundle\Model\UserInterface", cascade={"all"})
     * @ORM\JoinColumn(name="granted_by_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected ?UserInterface $grantedBy = null;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    protected array $roles = [];

    /**
     * CompleteAccountUserRelation constructor.
     */
    public function __construct()
    {
        $this->roles = [];
    }

    public function getGrantedBy(): ?UserInterface
    {
        return $this->grantedBy;
    }

    public function setGrantedBy(?UserInterface $grantedBy): void
    {
        $this->grantedBy = $grantedBy;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
