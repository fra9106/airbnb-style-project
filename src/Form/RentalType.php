<?php

namespace App\Form;

use App\Entity\Rental;
use App\Entity\Category;
use App\Form\Config\GetConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('coverImage', UrlType::class, $this->getConfigurationForm('Photo','Choisissez une photo pour votre annonce (URL)', ["required" => false]))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
