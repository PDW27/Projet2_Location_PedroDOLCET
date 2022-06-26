<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('civilite', ChoiceType::class , [
            "choices" => [
                "femme" => "f",
                "homme" => "m"
            ],
            "placeholder" => "--choisir--"
        ])
        ->add('prenom')
        ->add('nom')
        ->add('pseudo')
            ->add('email')
            ->add('role' , ChoiceType::class , [
                "mapped" => false ,
                "required" => false,
                "choices" => [
                    "membre" => "ROLE_MEMBRE",
                    "admin" => "ROLE_ADMIN"
                ],
                "placeholder" => "--choisir--",
                "label" => "role : laisser vide pour ne pas le changer"
            ])
            ->add('plainPassword', PasswordType::class, [
                "mapped" => false , "required" => false , "label" => "mot de passe : laisser vide pour ne pas le changer"
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
