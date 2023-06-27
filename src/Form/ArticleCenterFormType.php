<?php

namespace App\Form;

use App\Entity\ArticleCenter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCenterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Title', TypeTextType::class,[
            'attr' => ['class' => 'inputclass'],
            'label' => 'Titre'
            ])
            ->add('Text', TextareaType::class, [
                'attr' => ['class' => 'inputclass'],
                'label' => 'Texte'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticleCenter::class,
        ]);
    }
}
