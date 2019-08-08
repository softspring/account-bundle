<?php

namespace Softspring\AccountBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Softspring\Account\Manager\AccountManagerInterface;
use Softspring\Account\Model\AccountInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AccountManager implements AccountManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * AccountManager constructor.
     *
     * @param EntityManagerInterface  $em
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EntityManagerInterface $em, EncoderFactoryInterface $encoderFactory)
    {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        $metadata = $this->em->getClassMetadata(AccountInterface::class);
        return $metadata->getName();
    }

    /**
     * @return EntityRepository
     */
    public function getRepository(): EntityRepository
    {
        return $this->em->getRepository(AccountInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function create(): AccountInterface
    {
        $className = $this->getClass();
        return new $className();
    }

    /**
     * @param AccountInterface $account
     *
     * @throws \Exception
     */
    public function save(AccountInterface $account): void
    {
        $this->em->persist($account);
        $this->em->flush();
    }

    /**
     * @param AccountInterface $account
     *
     * @throws \Exception
     */
    public function delete(AccountInterface $account): void
    {
        $this->em->remove($account);
        $this->em->flush();
    }

    /**
     * @inheritdoc
     */
    public function findAccountBy(array $criteria): ?AccountInterface
    {
        return $this->getRepository()->findOneBy($criteria);
    }
}