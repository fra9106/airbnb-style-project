<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/rental/{slug}/booking", name="app_booking")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function booking(Rental $rental, Request $request, EntityManagerInterface $manager): Response
    {

        $booking = new Booking();
        $booking->setBooker($this->getUser())
                ->setRental($rental);

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    "Dates indisponibles 😕 ."
                );
            } else {

                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute(
                    'app_booking_show',
                    [
                        'id' => $booking->getId(),
                        'withAlert' => true
                    ]
                );
            }


            
        }
        return $this->render('booking/booking.html.twig', [
            'rental' => $rental,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking-show/{id}", name="app_booking_show")
     *
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function bookingShow(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser())
                ->setRental($booking->getRental());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $manager->persist($comment);
                $manager->flush();

                $this->addFlash(
                    'message',
                    "Votre commentaire a bien été pris en compte !👍😉 "
                );
        }

        return $this->render("booking/booking_show.html.twig", [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
