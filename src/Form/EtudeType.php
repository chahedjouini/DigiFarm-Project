<?php

namespace App\Form;

use App\Entity\Culture;
use App\Entity\Etude;
use App\Entity\Expert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_user')
            ->add('type_etude')
            ->add('date_r', null, [
                'widget' => 'single_text',
            ])
            ->add('cout')
            ->add('culture', EntityType::class, [
                'class' => Culture::class,
                'choice_label' => 'id',
            ])
            ->add('expert', EntityType::class, [
                'class' => Expert::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etude::class,
        ]);
    }
}
