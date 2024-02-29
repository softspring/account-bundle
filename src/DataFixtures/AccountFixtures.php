<?php

namespace Softspring\AccountBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\UserBundle\DataFixtures\UserFixtures;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\UserInterface;

class AccountFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    protected AccountManagerInterface $accountManager;

    public function __construct(AccountManagerInterface $accountManager)
    {
        $this->accountManager = $accountManager;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(UserInterface::class)->findAll();

        for ($i = 0; $i < 300; ++$i) {
            $account = $this->createAccount($manager);

            if ($account instanceof OwnerInterface) {
                $account->setOwner($users[array_rand($users)]);
            }

            $manager->persist($account);
        }

        $manager->flush();
    }

    protected function createAccount(ObjectManager $manager): AccountInterface
    {
        $faker = \Faker\Factory::create('es_ES');

        $account = $this->accountManager->createEntity();

        $account->setName($faker->company());

        return $account;
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['sfs_account'];
    }
}
