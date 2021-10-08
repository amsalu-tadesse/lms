<?php

namespace App\Form;

use App\Entity\Content;
use App\Entity\InstructorCourseChapter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         
        $builder
            ->add('chapter'/*, EntityType::class, [
                'class' => InstructorCourseChapter::class,
                'required' => false,
                'placeholder' => "",
                // 'choice_value' => 'sectionLabel',
                'query_builder' => function (EntityRepository $er, $options) {
                    $res = $er->createQueryBuilder('s')
                             ->join('s.instructorCourse', 'ic')
                             ->andWhere('ic.id =: instcrs')
                ->setParameter('instcrs', $options['instcrs']);
                    return $res;
                },
            ]*/) 
            ->add('title')
            ->add('content')
            // ->add('file')
            ->add('videoLink')
            // ->add('instructorCourse')
            ->add('filename', FileType::class, [
                'label' => 'Resource',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '250M',
                        /*'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],*/
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>  Content::class
            // 'data_class' => ['content'=>Content::class, 'instcrs'=>null]
            // 'data_class' => null,
        ]);
    }
}