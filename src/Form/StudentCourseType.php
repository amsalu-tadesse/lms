<?php

namespace App\Form;

use App\Entity\StudentCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('status', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('active', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('isAtPage', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('student', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('instructorCourse', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('completedAt', null, [
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
