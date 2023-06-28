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
            ->add('name', TextType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Nom :',
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Prenom :',
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Email :',
            ])
            ->add('date', DateType::class, [
                'attr' => ['class' => 'inputclass'],
                'widget' => 'choice',
                'data' => new \DateTime(),
                'format' => 'dd-MM-yyyy',
            ])
            ->add('dateFin', DateType::class, [
                'attr' => ['class' => 'inputclass'],
                'widget' => 'choice',
                'data' => new \DateTime(),
                'format' => 'dd-MM-yyyy',
            ])
            ->add('livraison')
            ->add('lieu', TextType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Lieu :',
            ])
            ->add('commande', TextareaType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Commande :',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
