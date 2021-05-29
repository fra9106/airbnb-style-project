<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Rental;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminBookingEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('endAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('comment', TextType::class)
            ->add('booker', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getFirstName() . " " . strtoupper($user->getLastName());
                }
            ])
            ->add('rental', EntityType::class, [
                'class' => Rental::class,
                'choice_label' => 'title'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => ['booking'],
        ]);
    }
}
