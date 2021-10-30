<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('instruction')
            ->add('percentage')
            ->add('passvalue')
            ->add('active', ChoiceType::class,[
                'choices'=> [
                    'no'=> '0',
                    'yes'=>'1'
                ],
            ])
            ->add('activeQuestions',null, [
                'attr' =>['placeholder' => 'total number of questions visible for the student']
            ])
            // ->
            ->add('instructorCourseChapter')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
