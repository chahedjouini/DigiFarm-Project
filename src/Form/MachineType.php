<?php

namespace App\Form;

use App\Entity\Machine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\EtatEquipement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('type')
            ->add('date_achat', null, [
                'widget' => 'single_text',
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Actif' => EtatEquipement::ACTIF,
                    'Inactif' => EtatEquipement::INACTIF,
                    'En Maintenance' => EtatEquipement::EN_MAINTENANCE,
                ],
                'expanded' => false, // true pour boutons radio
                'multiple' => false, // true pour sélection multiple
            ])
           
            ->add('etat_pred')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}
