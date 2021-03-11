<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 180,
                        "maxMessage" => "Le nom d'auteur doit comporter 180 caractères maximum",
                        "min" => 2,
                        "minMessage" => "Le nom d'auteur doit comporter au moins 2 caractères"
                    ]),
                    new NotBlank([
                        "message" => "Le nom d'auteur ne peut être vide"
                    ])
                ]
            ])
            // ->add('roles')
            ->add('password', PasswordType::class)
            ->add('nom', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le nom d'auteur doit comporter 30 caractères maximum",
                        "min" => 2,
                        "minMessage" => "Le nom d'auteur doit comporter au moins 2 caractères"
                    ]),
                    new NotBlank([
                        "message" => "Le nom d'auteur ne peut être vide"
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le nom d'auteur doit comporter 30 caractères maximum",
                        "min" => 2,
                        "minMessage" => "Le nom d'auteur doit comporter au moins 2 caractères"
                    ]),
                    new NotBlank([
                        "message" => "Le nom d'auteur ne peut être vide"
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
            'data_class' => Abonne::class,
        ]);
    }
}
