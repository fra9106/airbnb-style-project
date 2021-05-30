<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Config\GetConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProfileFormType extends GetConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

        ->add('first_name', TextType::class, $this->getConfigurationForm('Prénom','Votre prénom'))
        ->add('last_name', TextType::class, $this->getConfigurationForm('Nom','Votre nom'))
        ->add('email',  EmailType::class, $this->getConfigurationForm('Email','Votre email'))
        ->add('picture', UrlType::class, $this->getConfigurationForm('Avatar','Choisisez un avatar'))
        ->add('introduction', TextType::class, $this->getConfigurationForm('Into','Choisisez une phrase qui vous représente le mieux'))
        ->add('description', TextareaType::class, $this->getConfigurationForm('Description','Une petite description de votre personalité'))   
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
