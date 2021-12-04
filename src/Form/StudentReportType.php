<?php

namespace App\Form;

use App\Entity\StudentCourse;
use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('courses', EntityType::class, [
                  'class' => Course::class,  
                  'mapped' => false,          
                  'choice_label' => 'name',
                  'attr' => ['class'=>'form-control']
               ])
               ->add('status', ChoiceType::class,[
                  'mapped' => false,
                  'attr' => ['class'=>'form-control'],
                  'choices'  => [
                     'All' => 2,
                     'Finished' => 1,
                     'UnFinished' => 0,
                  ],
                  ])
               ->add('search', SubmitType::class, ['label' => 'Search', 'attr' => ['class'=>'btn btn-primary']])
               ->add('export', SubmitType::class, ['label' => 'Export To Excel','attr' => ['class'=>'btn btn-success']]);          
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentCourse::class,
        ]);
    }
}
