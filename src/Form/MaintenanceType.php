<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\Maintenance;
use App\Entity\Technicien;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\StatutMaintenance;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class MaintenanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEntretien', null, [
                'widget' => 'single_text',
            ])
            ->add('cout')
            ->add('temperature')
            ->add('humidite')
            ->add('consoCarburant')
            ->add('consoEnergie')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => StatutMaintenance::EN_ATTENTE,
                    'En cours' => StatutMaintenance::EN_COURS,
                    'Terminée' => StatutMaintenance::TERMINEE,
                ],
                'expanded' => false, // true si tu veux des boutons radio
                'multiple' => false, // true pour une sélection multiple
                'choice_label' => fn ($choice) => $choice->value, // Affiche la valeur de l'énumération
            ])
            ->add('idMachine', EntityType::class, [
                'class' => Machine::class,
                'choice_label' => 'id',
            ])
            ->add('idTechnicien', EntityType::class, [
                'class' => Technicien::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
        ]);
    }
}
