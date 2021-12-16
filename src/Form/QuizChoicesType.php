<?php

namespace App\Form;

use App\Entity\QuizChoices;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizChoicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('letter')
            ->add('description')
            ->add('quiz')
        ;


        $builder->addEventSubscriber(new AddNameFieldSubscriber());

        /*  $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
              // ... adding the name field if needed
              $quizChoice = $event->getData();
              $form = $event->getForm();

              // checks if the Product object is "new"
              // If no data is passed to the form, the data is "null".
              // This should be considered a new "Product"
              if (!$product || null === $product->getId()) {
                  $form->add('name', TextType::class);
              }
          });*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuizChoices::class,
        ]);
    }
}
