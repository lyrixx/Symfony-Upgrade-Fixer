<?php

namespace Umpirsky\UpgradeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'form.name'))
            ->add('price1', 'text', array(
                'label' => 'form.price1',
                'scale' => 3,
            ))
            ->add('price2', 'text', array(
                'scale' => 3,
            ))
            ->add('discount', 'integer', [
                'label' => 'form.email',
                'inherit_data' => true,
            ])
            ->add('password', 'password')
        ;
    }
}
