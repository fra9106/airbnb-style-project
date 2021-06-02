<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users-list", name="admin_users_list")
     */
    public function adminUsersList(UserRepository $users): Response
    {
        return $this->render('admin/user/admin_users_list.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_user_edit")
     *
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function adminUserEdit(Request $request, User $user, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AdminUserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', "L'utilisateur : {$user->getFullName()} a bien été modifié 🤗");

            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/user/admin_user_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/user/{id}/delete", name="admin_user_delete")
     *
     * @param EntityManagerInterface $Manager
     * @return Response
     */
    public function adminUserDelete(EntityManagerInterface $manager, User $user): Response
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', "❌ La résa de : {$user->getFullName()} a bien été supprimée ");

        return $this->redirectToRoute('admin_users_list');
    }
    
}
