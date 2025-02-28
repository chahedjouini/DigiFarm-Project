<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\UserRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('AdresseMail')
            ->add('Password', PasswordType::class) // Sécurisation du champ mot de passe
            ->add('Role', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => UserRole::ADMIN,
                    'Agriculteur' => UserRole::AGRICULTEUR,
                    'Client' => UserRole::CLIENT,
                ],
                // Conversion de l'énum en chaîne de caractères
                'choice_value' => fn (?UserRole $role) => $role?->value,
                // Affichage du nom de l'énum dans le formulaire
                'choice_label' => fn (UserRole $role) => $role->name,
                'expanded' => false, // Liste déroulante
                'multiple' => false, // Un seul choix possible
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
