<?php

namespace App\Form;

use App\Entity\Animal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('type', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le type est obligatoire.']),
                ]
            ])
            ->add('age', IntegerType::class, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'L\'âge est obligatoire.']),
                    new Assert\Positive(['message' => 'L\'âge doit être un nombre positif.'])
                ]
            ])
            ->add('poids', NumberType::class, [
                'constraints' => [
                    new Assert\NotNull(['message' => 'Le poids est obligatoire.']),
                    new Assert\Positive(['message' => 'Le poids doit être un nombre positif.'])
                ]
            ])
            ->add('race', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => 50,
                        'maxMessage' => 'La race ne peut pas dépasser {{ limit }} caractères.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
