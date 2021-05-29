<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingEditType;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings-list", name="admin_bookings_list")
     */
    public function adminBookingsList(BookingRepository $bookings): Response
    {
        return $this->render('admin/booking/admin_boogings_list.html.twig', [
            'bookings' => $bookings->findAll()
        ]);
    }

    /**
     * @Route("/admin/booking/{id}/edit", name="admin_booking_edit")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Booking $booking
     * @return Response
     */
    public function adminBookingEdit(Request $request, EntityManagerInterface $manager, Booking $booking): Response
    {
        $form = $this->createForm(AdminBookingEditType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $booking->setAmount(0); // on remet le compteur à 0 pour le recalcul en @ORM\PreUpdate au cas on change d'annonce et que le prix de la nuit diffère

            //pas besoin de persister, la résa existe déjà!
            $manager->flush();

            $this->addFlash('success', "La résa numéro : {$booking->getId()} a bien été modififié 🤗");

            return $this->redirectToRoute('admin_bookings_list');
        }

        return $this->render('admin/booking/admin_booking_edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
     * @Route("/admin/booking/{id}/delete", name="admin_booking_delete")
     *
     * @param EntityManagerInterface $Manager
     * @return Response
     */
    public function adminBookingDelete(EntityManagerInterface $manager, Booking $booking): Response
    {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash('success', "❌ La résa de : {$booking->getBooker()->getFullName()} a bien été supprimée ");

        return $this->redirectToRoute('admin_bookings_list');
    }
}
