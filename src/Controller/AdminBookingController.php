<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
