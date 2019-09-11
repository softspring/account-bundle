<?php

namespace Softspring\AccountBundle\Form;

use Softspring\AccountBundle\Model\AccountInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as Types;

class SettingsForm extends AbstractType implements SettingsFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountInterface::class,
            'translation_domain' => 'sfs_account',
            'label_format' => 'settings.form.%name%.label',
            'validation_groups'=> ['Settings', 'Default'],
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', Types\TextType::class);
    }
}