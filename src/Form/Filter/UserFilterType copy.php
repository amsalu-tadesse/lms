<?php

namespace App\Form\Filter;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',null,array('required' => false))
            ->add('middleName',null,array('required' => false))
            ->add('lastName',null,array('required' => false))
            ->add('userType',null,array('required' => false))
            ->add('email',null,array('required' => false))
            ->add('username',null,array('required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
