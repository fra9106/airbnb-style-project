<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\Config\GetConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends GetConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', NumberType::class, $this->getConfigurationForm("Note sur 5", "Merci d'indiquer une note de 1 à 5 ", [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1
                ]
            ]))
            ->add('content', TextareaType::class, $this->getConfigurationForm("Votre avis et témoignage", " Merci de nous raconter votre expérience !"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
