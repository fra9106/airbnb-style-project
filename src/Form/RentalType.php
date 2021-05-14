<?php

namespace App\Form;

use App\Entity\Rental;
use App\Entity\Category;
use App\Form\Config\getConfigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class RentalType extends getConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfigurationForm('Titre de l\'annonce','Choisisez un titre pour votre annonce'))

            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
                ])
            ->add('introduction', TextType::class, $this->getConfigurationForm('Intro','Choisissez une introduction à votre annonce'))
            ->add('content', TextareaType::class, $this->getConfigurationForm('Description de l\'annonce','Donnez une description à votre annonce')) 
            ->add('rooms', IntegerType::class, $this->getConfigurationForm('Nombre de chambres','Tapez le nombre de chambres disponible'))
            ->add('price', MoneyType::class, $this->getConfigurationForm('Prix','Indiquez le prix de la nuit'))
            ->add('coverImage', UrlType::class, $this->getConfigurationForm('Photos','Choisissez une image pour votre annonce'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
