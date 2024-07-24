<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['autofocus' => true]])
            ->add('location')
            ->add('price', null, ['html5' => true, 'scale' => 2])
            ->add('description', null, ['attr' => ['rows' => 5]])
            ->add('startsAt')
            ->add('imageFileName', null, ['empty_data' => 'placeholder.jpg'])
            ->add('capacity', null, ['empty_data' => '1'])
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
