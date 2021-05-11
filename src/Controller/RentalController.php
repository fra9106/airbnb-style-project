<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Form\RentalType;
use App\Repository\RentalRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/new-rental", name="app_new_rental")
     *
     * @return Response
     */
    public function newRental(Request $request, EntityManagerInterface $manager)
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');
        $rental = new Rental();
        //$rental->setAuthor($this->getUser());
        $rental->setCreatedAt(new \Datetime());
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($rental);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre annonce <strong>{$rental->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('app_rental_show', [
                'slug' => $rental->getSlug(),
                'category_slug' => $rental->getCategory()->getSlug(),
            ]);;
        }


        return $this->render('rental/new_rental.html.twig', [
            'rental' => $rental,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/category/{slug}", name="app_rental_category")
     * 
     * @return Response
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

    /**
     * @Route("/{category_slug}/{slug}/", name="app_rental_show")
     *
     * @return Response
     */
    public function rentalShow(Rental $rental) // en utilisant le @paramConverter
    {
        return $this->render('rental/rental_show.html.twig', [
            'rental' => $rental,

        ]);
    }
}
