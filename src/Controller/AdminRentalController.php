<?php

namespace App\Controller;

use App\Entity\Rental;
use App\Form\RentalType;
use App\Repository\RentalRepository;
use Doctrine\ORM\EntityManagerInterface;
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


    /**
     *@Route("/admin/rental/{id}/edit", name="app_admin_rental_edit")
     *
     * @param Rental $rental
     * @param Request $request
     * @return Response
     */
    public function adminRentalEdit(Rental $rental, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RentalType::class, $rental);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', "l'annonce : {$rental->getTitle()} a bien été modififié 🤗");
        }

        return $this->render('admin/rental/admin_rental_edit.html.twig', [
            'form' => $form->createView(),
            'rental' => $rental
        ]);
    }

    /**
     * @Route("/admin/rental/{id}/delete", name="app_admin_rental_delete")
     *
     * @param Rental $rental
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function adminRentalDelete(Rental $rental, EntityManagerInterface $manager): Response
    {
        if (count($rental->getBookings()) > 0) {
            $this->addFlash('warning', " ⚠ Attention vous ne pouvez pas supprimer l'annonce : {$rental->getTitle()} car cette annonce possède déjà des réservations !");
        } else {

            $manager->remove($rental);
            $manager->flush();

            $this->addFlash('success', "❌ L'annonce : {$rental->getTitle()} a bien été supprimée !");
        }
        return $this->redirectToRoute('app_admin_rentals_list');
    }
}
