<?php

namespace App\Form;

use App\Entity\QuestionAnswer;
use App\Entity\InstructorCourse;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class QuestionAnswerNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inid = $options['inid'];


        $builder

            ->add('course', EntityType::class, [
                'attr' => ['class'=>'form-control mb-4'],
                'class' => InstructorCourse::class,
                'required' => false,
                'placeholder' => "",
                'choice_value' => 'course',
                'query_builder' => function (EntityRepository $er) use ($inid) {
                    $res = $er->createQueryBuilder('ic')
                             ->andWhere('ic.instructor = :inid')
                ->setParameter('inid', $inid);
                    return $res;
                },
<<<<<<< HEAD
            ])
            ->add('question', TextareaType::class, [
                'attr'=>[
                    'class'=>'form-control mt-2',
                    'rows'=>7
                    ]
            ])
=======
            ]) 
            ->add('question', CKEditorType::class, array(
                'config' => array(
                    'uiColor' => '#ffffff',
                    //...
                ),
            ));
>>>>>>> 714b03afd891c174c9a43b712a3da491aea2374d
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('inid');
        $resolver->setDefaults([
            'data_class' => QuestionAnswer::class,
        ]);
    }
}
