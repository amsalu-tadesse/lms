<?php

namespace App\Form\Filter;

use App\Entity\StudentCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RequestFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('student', EmailType::class, [
                'attr'=>['class'=>'form-control'],
                'mapped' => false,
                'required' => false
            ])
            ->add('instructorCourse', null, [
                'attr'=>['class'=>'form-control'],
                'label'=>'course'
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
