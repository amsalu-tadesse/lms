<?php

namespace App\Form;

use App\Entity\QuizQuestions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class QuizQuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                 ),
            ))
            // ->add('answer')
            // ->add('quiz') 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizQuestions::class,
        ]);
    }
}
