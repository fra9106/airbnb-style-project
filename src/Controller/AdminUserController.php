<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users-list", name="admin_users_list")
     */
    public function adminUsersList(UserRepository $users): Response
    {
        return $this->render('admin/user/admin_users_list/index.html.twig', [
            'users' => $users->findAll(),
        ]);
    }
}
