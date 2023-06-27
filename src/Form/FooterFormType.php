<?php

namespace App\Form;

use App\Entity\Footer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TypeTextType::class,[
                'attr' => ['class' => 'inputclass'],
                'label' => 'Nom'
                ])
            ->add('address', TypeTextType::class,[
                'attr' => ['class' => 'inputclass'],
                'label' => 'Adresse'
                ])
            ->add('city', TypeTextType::class,[
                'attr' => ['class' => 'inputclass'],
                'label' => 'Ville'
                ])
            ->add('email', EmailType::class,[
                'attr' => ['class' => 'inputclass'],
                'label' => 'Email'
                ])
            ->add('phone' , TypeTextType::class,[
                'attr' => ['class' => 'inputclass'],
                'label' => 'Nom'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Footer::class,
        ]);
    }
}
