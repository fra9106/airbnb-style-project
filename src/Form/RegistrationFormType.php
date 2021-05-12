<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, [
                'attr' => ['placeholder' => 'Votre nom'],
                'required' => false,
            ])
            ->add('last_name', TextType::class, [
                'attr' => ['placeholder' => 'Votre prénom'],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Votre adresse mail'],
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['placeholder' => 'Choisissez un mot de passe'],
                'required' => false,
            ])
            ->add('confirm_password', PasswordType::class, [
                'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                'required' => false,
            ])
            ->add('picture', UrlType::class, [
                'attr' => ['placeholder' => 'Choisisez un avatar'],
                'required' => false,
            ]) 
            ->add('introduction', TextType::class, [
                'attr' => ['placeholder' => 'Choisisez une phrase qui vous représente le mieux'],
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['placeholder' => 'Une petite description de votre personalité'],
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez cochez cette case d\'agrément.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
