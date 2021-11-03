<?php

namespace App\Form;

use App\Entity\QuestionAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionAnswerNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('course',null,[
                'attr' => ['class'=>'form-control']
            ])
            ->add('title',null,[
                'attr'=>['class'=>'form-control mt-1']
            ])
            ->add('question',TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control mt-2',
                    'rows'=>7
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionAnswer::class,
        ]);
    }
}
