<?php
// src/Form/SuiviType.php

namespace App\Form;

use App\Entity\Suivi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SuiviType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('temperature', NumberType::class, [
                'label' => 'Température (°C)',
                'scale' => 2,  // Optionally define scale (decimal places)
                'attr' => [
                    'min' => 30,  // Minimum temperature value (valid range)
                    'max' => 45   // Maximum temperature value (valid range)
                ]
            ])
            ->add('rythme_cardiaque', NumberType::class, [
                'label' => 'Rythme Cardiaque',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État de l\'animal',
                'choices' => [
                    'Bon' => 'Bon',
                    'Moyen' => 'Moyen',
                    'Critique' => 'Critique',
                ],
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('veterinaire', TextType::class, [
                'label' => 'Nom du vétérinaire',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('id_client', NumberType::class, [
                'label' => 'ID du client',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Suivi::class,
        ]);
    }
}
