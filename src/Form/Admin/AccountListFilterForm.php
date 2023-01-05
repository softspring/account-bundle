<?php

namespace Softspring\AccountBundle\Form\Admin;

use Softspring\AccountBundle\Manager\AccountManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\Component\DoctrinePaginator\Form\PaginatorForm;
use Softspring\UserBundle\Manager\UserManagerInterface;
use Softspring\UserBundle\Model\NameSurnameInterface;
use Softspring\UserBundle\Model\OwnerInterface;
use Softspring\UserBundle\Model\UserWithEmailInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountListFilterForm extends PaginatorForm implements AccountListFilterFormInterface
{
    protected AccountManagerInterface $accountManager;
    protected UserManagerInterface $userManager;

    public function __construct(AccountManagerInterface $accountManager, UserManagerInterface $userManager)
    {
        parent::__construct($accountManager->getEntityManager());
        $this->accountManager = $accountManager;
        $this->userManager = $userManager;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'translation_domain' => 'sfs_account',
            'label_format' => 'admin_accounts.list.filter_form.%name%.label',
            'class' => AccountInterface::class,
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

        if ($this->accountManager->getEntityClassReflection()->implementsInterface(OwnerInterface::class)) {
            $fields = [];

            if ($this->userManager->getEntityClassReflection()->implementsInterface(NameSurnameInterface::class)) {
                $fields[] = 'owner.name__like';
                $fields[] = 'owner.surname__like';
            }

            if ($this->userManager->getEntityClassReflection()->implementsInterface(UserWithEmailInterface::class)) {
                $fields[] = 'owner.email__like';
            }

            if (!empty($fields)) {
                $builder->add('owner', TextType::class, [
                    'property_path' => '['.implode('___or___', $fields).']',
                ]);
            }
        }

        $builder->add('search', SubmitType::class, [
            'label' => 'admin_accounts.list.filter_form.actions.search',
        ]);
    }
}
