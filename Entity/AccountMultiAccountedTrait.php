<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Softspring\Account\Model\AccountUserRelationInterface;
use Softspring\User\Model\UserInterface;

trait AccountMultiAccountedTrait
{
    /**
     * @var AccountUserRelationInterface[]|Collection
     * @ORM\OneToMany(targetEntity="Softspring\Account\Model\AccountUserRelationInterface", mappedBy="account", cascade={"all"})
     */
    protected $userRelations;

    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection
    {
        return $this->userRelations;
    }

    /**
     * @param AccountUserRelationInterface $userRelation
     */
    public function addRelation(AccountUserRelationInterface $userRelation): void
    {
        if (!$this->userRelations->contains($userRelation)) {
            $this->userRelations->add($userRelation);
        }
    }

    /**
     * @param AccountUserRelationInterface $userRelation
     */
    public function removeRelation(AccountUserRelationInterface $userRelation): void
    {
        if ($this->userRelations->contains($userRelation)) {
            $this->userRelations->removeElement($userRelation);
        }
    }

    /**
     * @return UserInterface[]|Collection
     */
    public function getUsers(): Collection
    {
        return $this->userRelations->map(function(AccountUserRelationInterface $userRelation) {
            return $userRelation->getUser();
        });
    }

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user): void
    {
        $relations = $this->getRelations()->filter(function (AccountUserRelationInterface $relation) use ($user) {
            return $relation->getUser() === $user;
        });

        foreach ($relations as $relation) {
            $this->removeRelation($relation);
        }

        if ($this->getOwner() === $user) {
            $this->setOwner(null);
        }
    }
}