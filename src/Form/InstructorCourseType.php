<?php

namespace App\Form;

use App\Entity\InstructorCourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\CourseRepository;
class InstructorCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $courseList = $options['courses']; 
        $builder
            // ->add('createdAt')
            ->add('active')
            ->add('course', null,[
                'query_builder' => function (CourseRepository $courseRepository)use($courseList) {
                    return $courseRepository->createQueryBuilder('cr')
                        ->where('cr.id IN (:ids)')
                        ->setParameter(':ids', $courseList);
                },
            ])
            ->add('instructor')
            // ->add('status')
            // ->add('duration')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('courses');
        $resolver->setDefaults([
            'data_class' => InstructorCourse::class,
        ]);
    }
}
