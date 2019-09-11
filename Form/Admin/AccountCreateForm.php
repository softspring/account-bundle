<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountCreateForm extends AbstractType implements AccountCreateFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountInterface::class,
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.create.form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('owner');
    }
}