<?php

namespace Softspring\AccountBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Softspring\AccountBundle\Model\AccountUserRelationInterface;
use Softspring\UserBundle\Model\UserInterface;

trait AccountMultiAccountedTrait
{
    /**
     * @var AccountUserRelationInterface[]|Collection
     * @ORM\OneToMany(targetEntity="Softspring\AccountBundle\Model\AccountUserRelationInterface", mappedBy="account", cascade={"all"})
     */
    protected Collection $userRelations;

    /**
     * @return AccountUserRelationInterface[]|Collection
     */
    public function getRelations(): Collection
    {
        $this->checkRelationsCollection();

        return $this->userRelations;
    }

    public function addRelation(AccountUserRelationInterface $userRelation): void
    {
        $this->checkRelationsCollection();

        if (!$this->userRelations->contains($userRelation)) {
            $this->userRelations->add($userRelation);
        }
    }

    public function removeRelation(AccountUserRelationInterface $userRelation): void
    {
        $this->checkRelationsCollection();

        if ($this->userRelations->contains($userRelation)) {
            $this->userRelations->removeElement($userRelation);
        }
    }

    /**
     * @return UserInterface[]|Collection
     */
    public function getUsers(): Collection
    {
        $this->checkRelationsCollection();

        return $this->userRelations->map(function (AccountUserRelationInterface $userRelation) {
            return $userRelation->getUser();
        });
    }

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

    /**
     * @throws \Exception
     */
    protected function checkRelationsCollection()
    {
        if (!$this->userRelations instanceof Collection) {
            throw new \Exception(sprintf('"%s" class must create a new collection for userRelations on construction', get_class($this)));
        }
    }
}
