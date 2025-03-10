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
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $incrsid = $options['incrsid'];
        $uploadSize = $options['uploadSize'];


        $builder
            ->add('chapter', EntityType::class, [
                'required' => true,
                'class' => InstructorCourseChapter::class,
                'required' => false,
                'placeholder' => "",
                // 'choice_value' => 'title',
                'query_builder' => function (EntityRepository $er) use ($incrsid) {
                    $res = $er->createQueryBuilder('s')
                             ->join('s.instructorCourse', 'ic')
                             ->andWhere('ic.id = :incrsid')
                ->setParameter('incrsid', $incrsid);
                    return $res;
                },
            ])
            ->add('title')
            // ->add('file')
            ->add('videoLink')
            ->add('content', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                ),
            ))
            // ->add('instructorCourse')
            ->add('filename', FileType::class, [

                // unmapped means that this field is not associated to any entity property
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'data_class' => null,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => $uploadSize.'M',
                        'mimeTypes' => [
                            'image/*',
                            'video/mp4',
                            'video/avi',
                            'video/mpeg',
                            'video/MOV',
                            'video/SWF',
                        ],
                        // 'mimeTypesMessage' => 'Please upload a valid PDF',
                    ])
                ],
            ])
            // ->add('instructorCourse')
            ->add('resource', FileType::class, [
                'label' => 'Additional Resource',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                'data_class' => null,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'help' => "only zip,word files, pdf, excel, image and zip are allowed (size allowed: <$uploadSize MB)",
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => $uploadSize.'M',
                        'mimeTypes' => [
                            'image/*',
                            'zip',
                            'application/zip',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'text/plain'
                        ],
                        // 'mimeTypesMessage' => "Please upload a valid Dcoument(zip,word files, pdf, excel, image and zip): size allowed: <$uploadSize MB",
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('incrsid');
        $resolver->setRequired('uploadSize');
        $resolver->setDefaults([
            'data_class' =>  Content::class
        ]);
    }
}
