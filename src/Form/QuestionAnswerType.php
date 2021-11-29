<?php

namespace App\Form;

use App\Entity\QuestionAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class QuestionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('videoAnswer', FileType::class, [
                'data_class' => null,
                'label' => 'Video Answer',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-2',
                    'id' => 'blob'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '40960k',
                        'mimeTypes' => [
                            'video/mp4',
                            'video/webm',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid video document',
                    ])
                ],
            ])
            ->add('answer',TextareaType::class,[
                'attr'=>[
                        'class'=>'form-control',
                        'rows'=>7,
                        'width' => '100%'
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
