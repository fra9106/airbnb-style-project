<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * account user logged
     * 
     * @Route("/account-user", name="app_account_user")
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function accountUser(): Response
    {
        return $this->render('user/profile_display.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/bookings-user", name="app_bookings_user")
     *
     * @return Response
     */
    public function bookingsUser():Response 
    {
        return $this->render('user/bookings_user_list.html.twig');
    }
}
