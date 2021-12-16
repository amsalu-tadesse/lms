<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',null, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Enter Course Name'
                )
            ])
            ->add('code',null, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Enter Course Code'
                )
            ])
            ->add('credit',IntegerType::class, [
                'required' => true,
                'label' => 'Credit Number',
                'attr' => array(
                    'placeholder' => 'Enter Credit number'
                )
            ])
            ->add('category',null, [
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Select Course Categroy'
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
