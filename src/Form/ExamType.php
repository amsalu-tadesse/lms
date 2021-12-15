<?php

namespace App\Form;

use App\Entity\Exam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('instruction', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('percentage', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('passvalue', null, [
                'attr'=>['class'=>'form-control']
            ])
            ->add('active', null, [
                'attr'=>['class'=>'form-status']
            ])
            ->add('instructorCourse', null, [
                'attr'=>['class'=>'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Exam::class,
        ]);
    }
}
