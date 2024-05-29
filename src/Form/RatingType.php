<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Rating;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('rate', ChoiceType::class, [
            'label' => 'Notez l\'article',
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
            'attr' => [
                'class' => 'form-select mt-4',
            ],
            'choices' => [
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
                '6' => 6,
                '7' => 7,
                '8' => 8,
                '9' => 9,
                '10' => 10,
            ],
        
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Noter l\'article',
            'attr' => [
                'class' => 'btn btn-primary mt-4',
            ],
        ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}
