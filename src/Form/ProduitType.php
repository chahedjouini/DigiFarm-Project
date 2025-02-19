<?php
namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

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
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image du produit',
                'mapped' => false, // Important : car ce champ n'existe pas dans l'entité
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG, WEBP).',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

