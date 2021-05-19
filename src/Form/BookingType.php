<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\Config\GetConfigType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends GetConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateType::class, $this->getConfigurationForm('Date d\'arrivée', 'Votre date d\'arrivée'))
            ->add('endAt', DateType::class, $this->getConfigurationForm('Date de départ', 'Votre date de départ'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
