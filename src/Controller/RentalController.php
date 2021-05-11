<?php

namespace App\Controller;

use App\Repository\RentalRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RentalController extends AbstractController
{

    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
    * @Route("/rentals-list", name="app_rentals_list")
    *
    * @return Response
    */
    public function RentalsList(RentalRepository $rentalRepository)
    {
        $rentals = $rentalRepository->findAll();
        return $this->render('rental/rental.html.twig', [
            'rentals' => $rentals
        ]);
    }

    /**
    * @Route("/{category_slug}/{slug}/", name="app_rental_show")
    *
    * @return Response
    */
    public function rentalShow($slug, RentalRepository $rentalRepository)
    {
        $rental = $rentalRepository->findOneBy([
            'slug' => $slug
        ]);

        return $this->render('rental/rental_show.html.twig', [
            'rental' => $rental
        ]);
    }
    
    /**
     * @Route("/category/{slug}", name="app_rental_category")
     */
    public function category($slug, CategoryRepository $repo): Response
    {
        $category = $repo->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("Cette catégorie n'existe pas !");
        }

        return $this->render('rental/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }
}
