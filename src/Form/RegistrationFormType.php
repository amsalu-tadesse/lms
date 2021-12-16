<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\AcademicLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName' ,null,[
                'attr'=>['class'=>'form-control mb-3']
            ])
            ->add('middleName' ,null,[
                'attr'=>['class'=>'form-control mb-3']
            ])
            ->add('lastName' ,null,[
                'attr'=>['class'=>'form-control mb-3']
            ])
            ->add('email' ,null,[
                'attr'=>['class'=>'form-control mb-3']
            ])
            ->add('sex' ,ChoiceType::class,[
                'required' => true,
                'attr'=>['class'=>'form-control'],
                'choices'  => [
                    'Male' => "m",
                    'Female' => "f",
                 ],
            ])
            ->add('academicLevel', EntityType::class, [
                'mapped' => false,
                'class' => AcademicLevel::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'attr'=>['class'=>'form-status'],
                'mapped' => false,
                'label' => 'Academic Level',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            // ->add('plainPassword', RepeatedType::class, [
            //     'type' => PasswordType::class,
            //     'invalid_message' => 'The password fields must match.',
            //     'options' => ['attr' => ['class' => 'password-field form-control']],
            //     'required' => true,
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             // max length allowed by Symfony for security reasons
            //             'max' => 4096,
            //         ]),
            //     ],
            //     'first_options'  => ['label' => 'Password'],
            //     'second_options' => ['label' => 'Repeat Password'],
            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
