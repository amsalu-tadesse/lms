<?php

namespace App\Form;

use App\Entity\AcademicLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcademicLevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
<<<<<<< HEAD
            ->add('name' ,null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('description' ,null,[
                'attr'=>['class'=>'form-control']
            ])
=======
            ->add('name')
            ->add('description')
>>>>>>> master
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AcademicLevel::class,
        ]);
    }
}
