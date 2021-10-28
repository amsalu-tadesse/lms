<?php

namespace App\Form\Filter;

use App\Entity\StudentCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('student' ,null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('instructorCourse' ,null,[
                'attr'=>['class'=>'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentCourse::class,
        ]);
    }
}
