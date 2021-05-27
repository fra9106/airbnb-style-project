<?php

namespace App\Controller;

use App\Repository\RentalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRentalController extends AbstractController
{
    /**
     * @Route("/admin/rentals-list", name="app_admin_rentals_list")
     */
    public function adminRentalsList(RentalRepository $rentals): Response
    {
        return $this->render('admin/rental/admin_rentals_list.html.twig', [
            "rentals" => $rentals->findAll()
        ]);
    }
}
