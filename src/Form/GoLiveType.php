<?php

namespace App\Form;

use App\Entity\GoLive;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use App\Entity\InstructorCourse;
use PHPUnit\Framework\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType as TypeDateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GoLiveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $uid = $options['uid'];

        $builder
            ->add('link', null, ['attr'=>['class'=>'form-control mb-4','placeholder'=>'Enter the live link here ...']])
            ->add('message', null, ['attr'=>['class'=>'form-control mb-4']])
            //->add('instructorCourse', null, ['attr'=>['class'=>'form-control mb-4']])
            ->add('active')

            ->add('instructorCourse', EntityType::class, [
                'required' => true,
                'label'=>'Course',
                'class' => InstructorCourse::class,
                'attr' => ['class'=>'form-control mb-4'],
                // 'placeholder' => "",
                // 'choice_value' => 'title',
                'query_builder' => function (EntityRepository $er) use ($uid) {

                    $res = $er->createQueryBuilder('ic')
                            //  ->join('App:GoLive', 'gl', 'with', 'gl.instructorCourse=ic.id')
                             ->join('ic.instructor', 'in')
                             ->join('in.user', 'u')
                             ->andWhere('u.id = :uid')
                            ->setParameter('uid', $uid);
                    return $res;
                },
            ])
            ->add('startsAt', TextType::class, ['attr'=>['class'=>'form-control mb-4','placeholder'=>'Enter the live link here ...']]);

           // ->add('startsAt', null, ['label'=>'Starts At','attr'=>['class'=>'startsAt form-control  mb-4']])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('uid');
        $resolver->setDefaults([
            'data_class' => GoLive::class,
        ]);


    }
}
