<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\AcademicLevel;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('middleName')
            ->add('lastName')
            // ->add('department')
            ->add('userType')
            ->add('email')
            ->add('academicLevel', EntityType::class, [
                'mapped' => false,
                'class' => AcademicLevel::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control mb-3', 'disabled' => 'disabled'],
            ])
            // ->add('username')
            // ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
