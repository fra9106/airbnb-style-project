<?php

namespace App\Form;

use App\Entity\Rental;
use App\Entity\Category;
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

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Titre de l\'annonce'],
                'required' => false,
            ])

            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                }
                ])
            ->add('introduction', TextType::class, [
                'label'=> 'Entête',
                'attr' => ['placeholder' => 'Entête de l\'annonce'],
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Description de l\'annonce'],
                'required' => false,
            ])
            ->add('rooms', IntegerType::class, [
                'label' => 'Nombre de chambres',
                'attr' => ['placeholder' => 'Indiquez le nombre de chambres'],
                'required' => false,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix (par nuit)',
                'attr' => ['placeholder' => 'Indiquez le prix de la nuit'],
                'required' => false,
            ])
            ->add('coverImage', UrlType::class, [
                'label' => 'Image',
                'attr' => ['placeholder' => 'Adresse url d\'une image'],
                'required' => false,
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
