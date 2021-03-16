<?php

namespace App\Form;

use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                "label" => "Titre du livre",
                "help" => "50 caractères maximum",
                "constraints" => [
                    new Length([
                        "max" => 50,
                        "maxMessage" => "Le titre doit comporter 50 caractères maximum",
                        "min" => 2,
                        "minMessage" => "Le titre doit comporter au moins 2 caractères"
                    ]),
                    new NotBlank([
                        "message" => "Le titre ne peut être vide"
                    ])
                ]
            ])
            ->add('auteur', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 50,
                        "maxMessage" => "Le nom d'auteur doit comporter 50 caractères maximum",
                        "min" => 2,
                        "minMessage" => "Le nom d'auteur doit comporter au moins 2 caractères"
                    ]),
                    new NotBlank([
                        "message" => "Le nom d'auteur ne peut être vide"
                    ])
                ]
            ])
            ->add('couverture', FileType::class, [
                "mapped" => false,
                "required" => false,
                "constraints" => [
                    new File([
                        "mimeTypes" => ["image/gif", "image/jpeg", "image/png"],
                        "mimeTypesMessage" => "Les formats autorisés sont gif, jpg et png",
                        "maxSize" => "2048k",
                        "maxSizeMessage" => "Le fichier ne doit pas faire plus de 2Mo"
                    ])
                ]
            ])
            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "btn btn-primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
