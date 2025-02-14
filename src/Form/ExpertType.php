<?php

namespace App\Form;

use App\Entity\Expert;
use App\Enum\Dispo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\DataTransformerInterface;

class ExpertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('email')
            ->add('zone')
            ->add('dispo', ChoiceType::class, [
                'choices' => [
                    'Disponible' => Dispo::DISPONIBLE->value,
                    'Non Disponible' => Dispo::NON_DISPONIBLE->value,
                ],
                'placeholder' => 'Choisir la disponibilité',
                'required' => true,
            ])
            // Add the data transformer to handle the conversion
            ->get('dispo')
            ->addModelTransformer(new class implements DataTransformerInterface {
                public function transform($value)
                {
                    // Transform the Dispo enum value to its string representation
                    return $value ? $value->value : null;
                }

                public function reverseTransform($value): ?Dispo
                {
                    // Reverse transform the string value back to Dispo enum
                    if ($value === Dispo::DISPONIBLE->value) {
                        return Dispo::DISPONIBLE;
                    } elseif ($value === Dispo::NON_DISPONIBLE->value) {
                        return Dispo::NON_DISPONIBLE;
                    }
                    return null; // Return null if not a valid Dispo value
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expert::class,
        ]);
    }
}
