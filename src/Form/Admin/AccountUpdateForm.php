<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\UserInterface;
use Softspring\UserBundle\Model\UserWithEmailInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountUpdateForm extends AbstractType implements AccountUpdateFormInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccountInterface::class,
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.update.form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
        $builder->add('owner', null, [
            'choice_label' => function (UserInterface $owner) {
                if ($owner instanceof NameSurnameInterface) {
                    return sprintf('%s %s', $owner->getName(), $owner->getSurname());
                }

                if ($owner instanceof UserWithEmailInterface) {
                    return $owner->getEmail();
                }

                return $owner->getUserIdentifier();
            },
        ]);
    }
}
