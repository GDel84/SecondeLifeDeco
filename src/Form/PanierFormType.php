<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Début',
                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime(),
            ])
            ->add('dateFin', DateType::class,[
                'label' => 'Fin',
                'format' => 'dd-MM-yyyy',
                'data' => new \DateTime(),
            ])
            ->add('livraison', ChoiceType::class, [
                'label' => 'livraison ?',
                'choices' => [
                    'Avec' => 'female',
                    'Sans' => 'Sans',
                ],
                'placeholder' => 'Option',
                'required' => true,
            ])
            ->add('lieu')
            ->add('Articles')
            //->add('quantity', IntegerType::class, [
            //    'label' => 'Quantité',
            //    'attr' => [
            //        'min' => 1,
            //        'max' => $options['article']->getQuantity(), // Limite maximale basée sur le stock disponible
            //    ],
            //])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
            'article' => null, // option pour passer l'entité Article
        ]);
    }
}
