<?php

declare(strict_types=1);

namespace Softspring\AccountBundle\Tests\Unit\Form\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Softspring\AccountBundle\Form\Admin\AccountDeleteForm;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\AccountMembershipsInterface;
use Softspring\AccountBundle\Model\UserAccountMembershipsInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class AccountDeleteFormTest extends TestCase
{
    public function testOnlyUsersBelongingExclusivelyToTheDeletedAccountCanBeDeleted(): void
    {
        $account = $this->createStubForIntersectionOfInterfaces([AccountInterface::class, AccountMembershipsInterface::class]);
        $otherAccount = $this->createStub(AccountInterface::class);
        $exclusiveUser = $this->createStubForIntersectionOfInterfaces([UserInterface::class, UserAccountMembershipsInterface::class]);
        $exclusiveUser->method('getAccounts')->willReturn(new ArrayCollection([$account]));
        $sharedUser = $this->createStubForIntersectionOfInterfaces([UserInterface::class, UserAccountMembershipsInterface::class]);
        $sharedUser->method('getAccounts')->willReturn(new ArrayCollection([$account, $otherAccount]));
        $unrelatedUser = $this->createStubForIntersectionOfInterfaces([UserInterface::class, UserAccountMembershipsInterface::class]);
        $unrelatedUser->method('getAccounts')->willReturn(new ArrayCollection([$otherAccount]));
        $plainUser = $this->createStub(UserInterface::class);
        $account->method('getUsers')->willReturn(new ArrayCollection([$exclusiveUser, $sharedUser, $unrelatedUser, $plainUser]));

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::once())->method('add')->with(
            'deleteUsersWithoutOtherAccounts',
            ChoiceType::class,
            self::callback(static fn (array $options): bool => [$exclusiveUser] === $options['choices']),
        )->willReturnSelf();

        (new AccountDeleteForm())->buildForm($builder, ['account' => $account]);
    }

    public function testAccountsWithoutMembershipsDoNotOfferUserDeletion(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder->expects(self::never())->method('add');

        (new AccountDeleteForm())->buildForm($builder, ['account' => $this->createStub(AccountInterface::class)]);
    }
}
