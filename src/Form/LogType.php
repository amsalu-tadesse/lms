<?php

namespace App\Form;

use App\Entity\Log;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('name', null, [
            //     'mapped' => false
            // ])
            // ->add('email', EmailType::class, [
            //     'mapped' => false
            // ])
            ->add('modifiedEntity', ChoiceType::class,[
                'required' => false,
                'attr'=>['class'=>'form-control'],
                'choices'  => [
                    'Academic Level' => "academicLevel",
                    'Content' => "content",
                    'Content Type' => "contentType",
                    'Course' => "course",
                    'Course Category' => "courseCategory",
                    'Exam' => "exam",
                    'Instructor' => "instructor",
                    'Instructor Course' => "instructorCourse",
                    'Instructor Course Chapter' => "instructorCourseChapter",
                    'Question' => "question",
                    'Question Answer' => "questionAnswer",
                    'Quiz' => "quiz",
                    'Quiz choices' => "quizChoice",
                    'Student' => "student",
                    'Student Answer' => "studentAnswer",
                    'Student-Chapter' => "studentChapter",
                    'Student-Course' => "studentCourse",
                    'Student-Question' => "studentQuestion",
                    'Student-Quiz' => "studentQuiz",
                    'System setting' => "systemSetting",
                    'User' => "User",
                    'User Group' => "userGroup",
                    'User Type' => "userType"
                 ],
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('action', ChoiceType::class,[
                'required' => false,
                'choices' => [
                    "Create" => 'create',
                    "Delete" => 'delete',
                    "Update" => 'update'
                ]
            ])
            ->add('actor', null, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Log::class,
        ]);
    }
}
