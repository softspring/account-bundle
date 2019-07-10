<?php

namespace Softspring\AccountBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountListFilterForm extends AbstractType implements AccountListFilterFormInterface
{
    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.list.filter_form.%name%.label',
            'csrf_protection' => false,
            'method' => 'GET',
            'required' => false,
            'attr' => ['novalidate'=>'novalidate'],
            'allow_extra_fields' => true,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'property_path' => '[nameContains]',
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'admin_accounts.list.filter_form.actions.search'
        ]);

        $builder->add(self::getOrderFieldParamName(), HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(self::getOrderDirectionParamName(), HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add(self::getRppParamName(), HiddenType::class, [
            'mapped' => false,
        ]);
    }

    public function getPage(Request $request): int
    {
        return (int) $request->query->get(self::getPageParamName(), 1);
    }

    public function getRpp(Request $request): int
    {
        return 50;
    }

    public function getOrder(Request $request): array
    {
        return [$request->query->get(self::getOrderFieldParamName(), 'name') => $request->query->get(self::getOrderDirectionParamName(), 'asc')];
    }


    public static function getPageParamName(): string
    {
        return 'page';
    }

    public static function getRppParamName(): string
    {
        return 'rpp';
    }

    public static function getOrderFieldParamName(): string
    {
        return 'sort';
    }

    public static function getOrderDirectionParamName(): string
    {
        return 'order';
    }
}