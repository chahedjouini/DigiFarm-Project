<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class StatistiquesVentesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('periode', ChoiceType::class, [
            'choices' => [
                'Jour' => 'day',
                'Semaine' => 'week',
                'Mois' => 'month',
                'Année' => 'year',
            ],
            'required' => true,
            'label' => 'Sélectionner la période',
        ])
        ->add('start_date', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'label' => 'Date de début',
        ])
        ->add('end_date', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'label' => 'Date de fin',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            // Configure your form options here
        ]);
    }
}
