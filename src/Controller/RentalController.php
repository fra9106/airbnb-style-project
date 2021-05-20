<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Form\RentalType;
use App\Repository\RentalRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $rentals = $rentalRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('rental/rental.html.twig', [
            'rentals' => $rentals
        ]);
    }

    /**
     * @Route("/new/rental", name="app_new_rental")
     * 
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function newRental(Request $request, EntityManagerInterface $manager)
    {

        $rental = new Rental();

        $rental->setCreatedAt(new \Datetime());
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($rental->getImages() as $image) {
                $image->setRental($rental);
                $manager->persist($image);
            }

            $rental->setAuthor($this->getUser());

            $manager->persist($rental);
            $manager->flush();


            $this->addFlash(
                'message',
                "Votre annonce : {$rental->getTitle()} a bien été enregistrée 🤩 !"
            );

            return $this->redirectToRoute(
                'app_rentals_list'
            );
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

    /**
     * @Route("/rental/{slug}/edit", name="app_rental_edit")
     * 
     * @Security("is_granted('ROLE_USER') and user === rental.getAuthor()", message="Vous n'êtes pas propriétaire de cette annonce, vous ne pouvez donc pas la modifier")
     *
     * @return Response
     */
    public function rentalEdit(Rental $rental, Request $request, EntityManagerInterface $manager):Response
    {
        $rental->setUpdateAt(new \Datetime());
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($rental->getImages() as $image) {
                $image->setRental($rental);
                $manager->persist($image);
            }


            $manager->persist($rental);
            $manager->flush();


            $this->addFlash(
                'message',
                "Votre annonce : {$rental->getTitle()} a bien été modifiée !"
            );

            return $this->redirectToRoute(
                'app_rentals_list'
            );
        }

        return $this->render('rental/edit_rental.html.twig', [
            'rental' => $rental,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/rental/{slug}/delete", name="app_rental_delete")
     * 
     * @Security("is_granted('ROLE_USER') and user === rental.getAuthor()", message="Vous n'êtes pas propriétaire de cette annonc, vous ne pouvez pas la supprimer")
     *
     * @return void
     */
    public function rentalDelete(Rental $rental, EntityManagerInterface $manager)
    {
        $manager->remove($rental);
        $manager->flush();

        $this->addFlash(
            'message',
            "Votre annonce a bien été supprimée !"
        );

        return $this->redirectToRoute(
            'app_rentals_list'
        );
    }
}
