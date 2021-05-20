<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Entity\Booking;
use App\Form\BookingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends AbstractController
{
    /**
     * @Route("/rental/{slug}/booking", name="app_rental_booking")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function Rentalbooking(Rental $rental, Request $request, EntityManagerInterface $manager): Response
    {

        $booking = new Booking();
        $booking->setBooker($this->getUser())
            ->setRental($rental);

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        //dd($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'message',
                "Votre réservation a bien été enregistrée 😍 !"
            );

            return $this->redirectToRoute(
                'app_homepage'
            );
        }
        return $this->render('booking/booking.html.twig', [
            'rental' => $rental,
            'form' => $form->createView()
        ]);
    }
}
