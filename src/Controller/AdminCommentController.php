<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments-list", name="admin_comments_list")
     */
    public function adminCommentsList(CommentRepository $comments): Response
    {
        return $this->render('admin/comment/admin_comments_list.html.twig', [
            'comments' => $comments->findAll()
        ]);
    }
}
