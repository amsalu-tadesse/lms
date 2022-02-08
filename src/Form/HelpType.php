<?php

namespace App\Form;

use App\Entity\Help;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class HelpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $uploadSize = $options['uploadSize'];
        $uploadSize = 100;
        $builder
            ->add('usertype')
            ->add('label')
            ->add('description')
            ->add('attachment', FileType::class, [

                // unmapped means that this field is not associated to any entity property
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'data_class' => null,
                // 'attr' => ['class'=>'form-control mb-4'],

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
                            'video/SWF',
                            'application/pdf',
                            'application/x-pdf',
                            'zip',
                            'application/zip',
                            'application/msword',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'text/plain'
                        ],
                         'mimeTypesMessage' => 'Please upload a valid Data',
                    ])
                ],
            ])
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('uploadSize');
        $resolver->setDefaults([
            'data_class' => Help::class,
        ]);
    }
}
