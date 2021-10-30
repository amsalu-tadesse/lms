<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\InstructorCourseChapter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $registeredChaptersid = $options['registeredChaptersid'];

        $builder
            ->add('name')
            ->add('instruction')
            ->add('percentage')
            ->add('passvalue')
            ->add('duration',null,[
                'label' => 'Durationa(in minutes)'
            ])


            ->add('instructorCourseChapter', EntityType::class, [
                'class' => InstructorCourseChapter::class,
                'required' => false,
                'placeholder' => "", 
                // 'choice_value' => 'title',
                'query_builder' => function (EntityRepository $er) 
                use($registeredChaptersid){


                    $chapter = '';
 
                    foreach ($registeredChaptersid as $value) {
                         
                        if($chapter == '')
                        {
                            $chapter = $value;
                        }
                        else 
                        {
                            $chapter .= ','.$value;
                        }
                    }

                    // dd($chapter); 

                    $res = $er->createQueryBuilder('s');
                    if($chapter != '')
                    {
                        $res = $res->leftjoin ('App:Quiz', 'q', 'WITH', 'q.instructorCourseChapter = s.id')
                        ->andWhere('s.id not in ('.$chapter.') ');
                    }
                      
             
                    return $res;
                },
            ]) 
            ->add('active')
            ->add('isMandatory')
            ->add('activeQuestions',null, [
                'attr' =>['placeholder' => 'total number of questions visible for the student']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('registeredChaptersid');
        $resolver->setDefaults([
            'data_class' => Quiz::class, 
        ]);
    }
}
