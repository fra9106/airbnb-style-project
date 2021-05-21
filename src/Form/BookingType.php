<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\Config\GetConfigType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class BookingType extends GetConfigType
{

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', TextType::class, $this->getConfigurationForm('Date d\'arrivée', 'Votre date d\'arrivée'))
            ->add('endAt', TextType::class, $this->getConfigurationForm('Date de départ', 'Votre date de départ'))
            ->add('comment', TextareaType::class, $this->getConfigurationForm(false, "Laissez ici votre commentaire", ["required" => false]))
        ;

        $builder->get('startAt')->addModelTransformer($this->transformer);
        $builder->get('endAt')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
