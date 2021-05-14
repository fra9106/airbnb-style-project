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
use App\Form\Config\GetConfigType;

class RegistrationFormType extends GetConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class,  $this->getConfigurationForm('Nom','Votre nom'))
            ->add('last_name', TextType::class,  $this->getConfigurationForm('prénom','Votre prénom'))
            ->add('email',  EmailType::class,  $this->getConfigurationForm('Email','Votre email'))
            ->add('password', PasswordType::class,  $this->getConfigurationForm('Mot de passe','Choisissez un mot de passe'))
            ->add('confirm_password', PasswordType::class,  $this->getConfigurationForm('Confimation de mot de passe','Confirmez votre mot de passe'))
            ->add('picture', UrlType::class,  $this->getConfigurationForm('Avatar','Choisisez un avatar'))
            ->add('introduction', TextType::class,  $this->getConfigurationForm('Into','Choisisez une phrase qui vous représente le mieux'))
            ->add('description', TextareaType::class,  $this->getConfigurationForm('Description','Une petite description de votre personalité'))
            
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
