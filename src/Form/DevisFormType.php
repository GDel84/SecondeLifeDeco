<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisFormType extends AbstractType
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
                'label' => 'Prénom :',
            ])
            ->add('email', EmailType::class,[
                'attr' => ['class' => 'inputclass'],
                'label' => 'e-mail :',
            ])
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'data' => new \DateTime(),
                'label' => 'Date de début :',
                'format' => 'dd-MM-yyyy', // Format inversé : jour-mois-année
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'choice',
                'data' => new \DateTime(),
                'label' => 'Date de retour :',
                'format' => 'dd-MM-yyyy', // Format inversé : jour-mois-année
            ])
            ->add('livraison', ChoiceType::class, [
                'attr' => ['class' => 'inputclass radio'],
                'label' => 'Avec livraison ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'multiple' => false, // Autoriser une seule sélection
            
            ])
            ->add('lieu', TextType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Lieu :',
                
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
