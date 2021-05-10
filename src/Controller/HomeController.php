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
    public function homepageRentalsList(RentalRepository $rentalRepository)
    {
        $rentals = $rentalRepository->findBy([], [], 3);
        
        return $this->render('home/home.html.twig', [
            'rentals' => $rentals
            
        ]);
    }
}
