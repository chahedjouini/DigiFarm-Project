<?php

namespace App\Form;

use App\Enum\Dispo;
use App\Entity\Expert;
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
                'expanded' => false,  // You can change this to true if you want radio buttons instead of a select dropdown
                'multiple' => false,
            ]);

        // Add a data transformer to handle enum conversion
        $builder->get('dispo')
            ->addModelTransformer(new class implements DataTransformerInterface {
                public function transform($value)
                {
                    // If the value is already an enum object, return its string value
                    return $value ? $value->value : null;
                }

                public function reverseTransform($value)
                {
                    // Convert the string value back to the Dispo enum
                    if ($value === Dispo::DISPONIBLE->value) {
                        return Dispo::DISPONIBLE;
                    } elseif ($value === Dispo::NON_DISPONIBLE->value) {
                        return Dispo::NON_DISPONIBLE;
                    }
                    return null; // Or handle the case where the value is invalid
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
