<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\Account\Model\AccountInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountInterface::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('id');
        $builder->add('name');
        $builder->add('owner');
    }
}