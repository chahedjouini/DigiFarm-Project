<?php

namespace App\Form;

use App\Entity\Etude;
use App\Enum\Climat;
use App\Enum\TypeSol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\DataTransformerInterface;

class EtudeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_r', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de l\'étude',
            ])
            ->add('culture', null, [
                'label' => 'Culture',
                'required' => true,
            ])
            ->add('expert', null, [
                'label' => 'Expert',
                'required' => true,
            ])
            ->add('climat', ChoiceType::class, [
                'choices' => [
                    'Sec' => Climat::SEC->value,
                    'Humide' => Climat::HUMIDE->value,
                    'Tempéré' => Climat::TEMPERE->value,
                ],
                'label' => 'Climat',
                'placeholder' => 'Choisir le climat',
                'required' => true,
            ])
            // Add the data transformer for Climat enum
            ->get('climat')
            ->addModelTransformer(new class implements DataTransformerInterface {
                public function transform($value)
                {
                    // Transform the Climat enum value to its string representation
                    return $value ? $value->value : null;
                }

                public function reverseTransform($value): ?Climat
                {
                    // Reverse transform the string value back to Climat enum
                    if ($value === Climat::SEC->value) {
                        return Climat::SEC;
                    } elseif ($value === Climat::HUMIDE->value) {
                        return Climat::HUMIDE;
                    } elseif ($value === Climat::TEMPERE->value) {
                        return Climat::TEMPERE;
                    }
                    return null; // Return null if not a valid Climat value
                }
            })
            ->add('type_sol', ChoiceType::class, [
                'choices' => [
                    'Argileux' => TypeSol::ARGILEUX->value,
                    'Sableux' => TypeSol::SABLEUX->value,
                    'Limoneux' => TypeSol::LIMONEUX->value,
                ],
                'label' => 'Type de sol',
                'placeholder' => 'Choisir le type de sol',
                'required' => true,
            ])
            // Add the data transformer for TypeSol enum
            ->get('type_sol')
            ->addModelTransformer(new class implements DataTransformerInterface {
                public function transform($value)
                {
                    // Transform the TypeSol enum value to its string representation
                    return $value ? $value->value : null;
                }

                public function reverseTransform($value): ?TypeSol
                {
                    // Reverse transform the string value back to TypeSol enum
                    if ($value === TypeSol::ARGILEUX->value) {
                        return TypeSol::ARGILEUX;
                    } elseif ($value === TypeSol::SABLEUX->value) {
                        return TypeSol::SABLEUX;
                    } elseif ($value === TypeSol::LIMONEUX->value) {
                        return TypeSol::LIMONEUX;
                    }
                    return null; // Return null if not a valid TypeSol value
                }
            })
            ->add('irrigation', CheckboxType::class, [
                'label' => 'Irrigation',
                'required' => false,
            ])
            ->add('fertilisation', CheckboxType::class, [
                'label' => 'Fertilisation',
                'required' => false,
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2,
                'required' => true,
            ])
            ->add('rendement', NumberType::class, [
                'label' => 'Rendement',
                'scale' => 2,
                'required' => true,
            ])
            ->add('precipitations', NumberType::class, [
                'label' => 'Précipitations',
                'scale' => 2,
                'required' => true,
            ])
            ->add('main_oeuvre', NumberType::class, [
                'label' => 'Main d\'œuvre',
                'scale' => 2,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etude::class,
        ]);
    }
}
