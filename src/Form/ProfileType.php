<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\AcademicLevel;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',null,[
                'attr' => ['class'=>'form-control']
            ])
            ->add('middleName',null,[
                'attr' => ['class'=>'form-control']
            ])
            ->add('lastName',null,[
                'attr' => ['class'=>'form-control']
            ])
            ->add('email',EmailType::class,[
                'attr' => ['class'=>'form-control']
            ])
            ->add('profilePicture', FileType::class, [

                // unmapped means that this field is not associated to any entity property
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control  profile-image-input'
                ],
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        // 'mimeTypesMessage' => 'Please upload a valid PDF',
                    ])
                ],
            ])
            // ->add('username')
            // ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
