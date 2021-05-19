<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Entity\Booking;
use App\Form\BookingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/rental/{slug}/booking", name="app_rental_booking")
     */
    public function Rentalbooking(Rental $rental): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        return $this->render('booking/booking.html.twig', [
            'rental' => $rental,
            'form' => $form->createView()
        ]);
    }
}
