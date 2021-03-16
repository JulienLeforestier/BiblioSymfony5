<?php

namespace App\Form;

use App\Entity\Abonne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;

class AbonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 180,
                        "maxMessage" => "Le pseudo doit comporter 180 caractères maximum",
                        "min" => 2,
                        "minMessage" => "Le pseudo doit comporter au moins 2 caractères"
                    ]),
                    new NotBlank([
                        "message" => "Le pseudo ne peut être vide"
                    ])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "Bibliothécaire" => "ROLE_BIBLIOTHECAIRE",
                    "Abonné" => "ROLE_ABONNE"
                ],
                "multiple" => true,
                "expanded" => true,
                "label" => "Rôles"
            ])
            ->add('password', PasswordType::class, [
                "constraints" => [
                    new Length([
                        "max" => 255,
                        "maxMessage" => "Le mot de passe doit comporter 255 caractères maximum",
                        "min" => 8,
                        "minMessage" => "Le mot de passe doit comporter au moins 8 caractères"
                    ]),
                    new PasswordStrength([
                        "minLength" => 8,
                        "minStrength" => 4
                    ]),
                    // new Regex([
                    //     "pattern" => "/^(?=.{6,10}$)(?=.*[a-z])(?=.[A-Z])(?=.*[0-9])(?=.*/W).*$/",
                    //     "message" => "Le mot de passe doit comporter entre 6 et 10 caractères, une minuscule, une majuscule, un chiffre et un caractère spécial"
                    // ]),
                    // new NotBlank([
                    //     "message" => "Le mot de passe ne peut être vide"
                    // ])
                ],
                /**
                 * Quand l'option 'mapped' vaut false, cela signifie que l'input 'password' ne doit pas être considéré comme une propriété
                 * de l'objet Abonne => si on remplit l'input, la valeur ne sera pas affectée directement à l'objet Abonne
                 * Du coup il faut gérer l'input côté controller
                 */
                "mapped" => false,
                "required" => false,
                "label" => "Mot de passe"
            ])
            ->add('nom', TextType::class, [
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le nom doit comporter 30 caractères maximum",
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                "label" => "Prénom",
                "constraints" => [
                    new Length([
                        "max" => 30,
                        "maxMessage" => "Le prénom doit comporter 30 caractères maximum",
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonne::class,
        ]);
    }
}
