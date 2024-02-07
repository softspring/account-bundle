<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AccountBundle\Model\MultiAccountedAccountInterface;
use Softspring\AccountBundle\Model\UserMultiAccountedInterface;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountDeleteForm extends AbstractType implements AccountDeleteFormInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountInterface::class,
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.delete.form.%name%.label',
        ]);

        $resolver->setDefined('account');
        $resolver->setAllowedTypes('account', [AccountInterface::class]);
        $resolver->setRequired('account');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var AccountInterface $account */
        $account = $options['account'];
        $users = $this->getDeletableUsers($account);

        if (!empty($users)) {
            $builder->add('deleteSingleAccountedUsers', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'choices' => $users,
                'choice_label' => function (UserInterface $user) {
                    return $user->getDisplayName();
                },
            ]);
        }
    }

    protected function getDeletableUsers(AccountInterface $account): array
    {
        $usersForDeletion = [];

        if ($account instanceof OwnerInterface) {
            $owner = $account->getOwner();
            // TODO check if user owns other things
        }

        if ($account instanceof MultiAccountedAccountInterface) {
            /** @var UserMultiAccountedInterface $user */
            foreach ($account->getUsers() as $user) {
                if (1 == $user->getAccounts()->count() && $user->getAccounts()->first() == $account) {
                    $usersForDeletion[] = $user;
                }
            }
        }

        return $usersForDeletion;
    }
}
