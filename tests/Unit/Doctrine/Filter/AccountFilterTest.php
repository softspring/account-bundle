<?php

namespace Softspring\AccountBundle\Tests\Unit\Doctrine\Filter;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\FilterCollection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Softspring\AccountBundle\Doctrine\Filter\AccountFilter;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountScopedInterface;
use stdClass;

class AccountFilterTest extends TestCase
{
    public function testAddsAccountConstraintForAccountAwareEntities(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection->expects(self::once())
            ->method('quote')
            ->with('account-42')
            ->willReturn("'account-42'");

        $filters = $this->createMock(FilterCollection::class);
        $filters->expects(self::once())
            ->method('setFiltersStateDirty');

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getConnection')->willReturn($connection);
        $entityManager->method('getFilters')->willReturn($filters);

        $filter = new AccountFilter($entityManager);
        $filter->setParameter('_account', 'account-42');

        $metadata = $this->createMetadata(AccountFilteredEntityStub::class);

        $constraint = $filter->addFilterConstraint($metadata, 'entity_alias');

        self::assertSame("entity_alias.account_id = 'account-42'", $constraint);
    }

    public function testReturnsEmptyConstraintForUnsupportedEntities(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $filter = new AccountFilter($entityManager);
        $metadata = $this->createMetadata(stdClass::class);

        $constraint = $filter->addFilterConstraint($metadata, 'entity_alias');

        self::assertSame('', $constraint);
    }

    private function createMetadata(string $class): ClassMetadata
    {
        return new class($class) extends ClassMetadata {
            public function __construct(string $class)
            {
                parent::__construct($class);
                $this->reflClass = new ReflectionClass($class);
            }
        };
    }
}

class AccountFilteredEntityStub implements AccountScopedInterface
{
    private ?AccountInterface $account = null;

    public function getAccount(): ?AccountInterface
    {
        return $this->account;
    }

    public function setAccount(?AccountInterface $account): void
    {
        $this->account = $account;
    }
}
