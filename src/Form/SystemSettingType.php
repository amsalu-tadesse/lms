<?php

namespace App\Form;

use App\Entity\SystemSetting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SystemSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value' , null,[
                'required' => true,
                'attr'=>['class'=>'form-control'],
            ])
            ->add('description',null,[
                'label' => 'Code Description'
            ])
            // ->add('active')
            // ->add('value')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SystemSetting::class,
        ]);
    }
}
