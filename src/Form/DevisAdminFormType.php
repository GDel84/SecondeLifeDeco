<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisAdminFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime_immutable'
            ])
            ->add('livraison')
            ->add('lieu', TextType::class)
            ->add('commande', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
