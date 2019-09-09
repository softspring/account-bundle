<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\Account\Model\AccountInterface;
use Softspring\Account\Model\MultiAccountedAccountInterface;
use Softspring\Account\Model\UserMultiAccountedInterface;
use Softspring\User\Model\UserInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountDeleteForm extends AbstractType implements AccountDeleteFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var MultiAccountedAccountInterface $account */
        $account = $options['account'];
        $users = $this->getDeletableUsers($account);

        if (!empty($users)) {
            $builder->add('deleteSingleAccountedUsers', ChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'choices' => $users,
                'choice_label' => function (UserInterface $user) {
                    return $user->getEmail();
                },
            ]);
        }
    }

    protected function getDeletableUsers(MultiAccountedAccountInterface $account): array
    {
        $usersForDeletion = [];

        /** @var UserMultiAccountedInterface $user */
        foreach($account->getUsers() as $user) {
            if ($user->getAccounts()->count() == 1 && $user->getAccounts()->first() == $account) {
                $usersForDeletion[] = $user;
            }
        }

        return $usersForDeletion;
    }
}