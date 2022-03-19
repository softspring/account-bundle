<?php

namespace Softspring\AccountBundle\Form\Admin;

use Jhg\DoctrinePaginationBundle\Request\RequestParam;
use Softspring\CrudlBundle\Form\EntityListFilterForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountListFilterForm extends EntityListFilterForm implements AccountListFilterFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.list.filter_form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name', TextType::class, [
            'property_path' => '[nameContains]',
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'admin_accounts.list.filter_form.actions.search',
        ]);
    }

    public static function orderValidFields(): array
    {
        return ['name'];
    }

    public static function orderDefaultField(): string
    {
        return 'name';
    }

    public function getRpp(Request $request): int
    {
        return 10;
    }
}
