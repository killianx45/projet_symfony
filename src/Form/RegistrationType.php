<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => 'Adresse Email',
            'attr' => [
                'class' => 'form-control',
                'minlength' => 2,
                'maxlength' => 180,
            ],
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
            'constraints' => [
                new Assert\Length([
                    'min' => 2,
                    'max' => 180,
                ]),
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
        ])
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ],
            'second_options' => [
                'label' => 'Confirmer le mot de passe',
                'attr' => [
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ],
            'invalid_message' => 'Les mots de passe ne correspondent pas.',
        ])
        ->add('age', IntegerType::class, [
            'label' => 'Age',
            'attr' => [
                'class' => 'form-control',
                'min' => 16,
                'max' => 120,
            ],
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
            'constraints' => [
                new Assert\Range([
                    'min' => 16,
                    'max' => 120,
                ]),
                new Assert\NotBlank(),
            ],
        ])
        ->add('sex', ChoiceType::class, [
            'label' => 'Sexe',
            'attr' => [
                'class' => 'form-select',
            ],
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
            'choices' => [
                'Homme' => false,
                'Femme' => true,
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Register',
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
