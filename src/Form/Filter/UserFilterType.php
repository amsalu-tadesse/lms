<?php

namespace App\Form\Filter;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array('required' => false))
            ->add('middleName', null, array('required' => false))
            ->add('lastName', null, array('required' => false))
            // ->add('department',null,array('required' => false))
            ->add('userType', null, array('required' => false))
            // ->add('store',null,array('required' => false))
            ->add('sex' ,ChoiceType::class,[
                'required' => false,
                'attr'=>['class'=>'form-control'],
                'choices'  => [
                    'Male' => "m",
                    'Female' => "f",
                 ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
