<?php

namespace App\Form;

use App\Entity\Registration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['autofocus' => true]])
            ->add('email')
            ->add('howHeard', ChoiceType::class, [
                'placeholder' => 'Please choose an option',
                'choices' => [
                    'Facebook' => 'Facebook',
                    'Twitter' => 'Twitter',
                    'Blog Post' => 'Blog Post',
                    'Web Search' => 'Web Search',
                    'Friend/Coworker' => 'Friend/Coworker',
                    'Newsletter' => 'Newsletter',
                    'Other' => 'Other',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Registration::class,
        ]);
    }
}
