<?php
namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Achat' => 'Achat',
                    'Vente' => 'Vente',
                ],
                'label' => 'Type de Produit'
            ])
            ->add('reference', TextType::class, [
                'label' => 'Référence'
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            ->add('stock', NumberType::class, [
                'label' => 'Stock'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

