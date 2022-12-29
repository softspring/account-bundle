<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\Component\DoctrinePaginator\Form\PaginatorFiltersForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountListFilterForm extends PaginatorFiltersForm implements AccountListFilterFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.list.filter_form.%name%.label',
            'rpp_valid_values' => [20],
            'rpp_default_value' => 20,
            'order_valid_fields' => ['name'],
            'order_default_value' => 'name',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name', TextType::class, [
            'property_path' => '[name__like]',
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'admin_accounts.list.filter_form.actions.search',
        ]);
    }
}
