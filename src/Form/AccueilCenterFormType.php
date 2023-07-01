<?php

namespace App\Form;

use App\Entity\AccueilCenter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AccueilCenterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class,[
            'attr' => ['class' => 'inputclass'],
            'label' => 'Titre'
            ])
        ->add('text', TextareaType::class, [
            'attr' => ['class' => 'inputclass'],
            'label' => 'Texte'
            ])
        ->add('picture', FileType::class, [
            'attr' => ['class' => 'inputclass'],
            'label' => 'Photo',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Vérifiez que vos image soi au format jpg ou jpeg',
                ])
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AccueilCenter::class,
        ]);
    }
}
