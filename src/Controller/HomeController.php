<?php

namespace App\Controller;

use App\Repository\RentalRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     *
     * @return void
     */
    public function homepageRentalsList(RentalRepository $rentals, RentalRepository $rentalsByOrderDateDesc, UserRepository $users)
    {

        return $this->render('home/home.html.twig', [
            'rentalsByOrderDateDesc' => $rentalsByOrderDateDesc->findByOrderDateDesc(3),
            'rentals' => $rentals->findBestRentals(3),
            'users' => $users->findBestUsers(2)

        ]);
    }
}
