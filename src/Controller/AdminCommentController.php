<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AdminCommentEditType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    /**
     *@Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Comment $comment
     * @return Response
     */
    public function adminCommentEdit(Request $request, EntityManagerInterface $manager, Comment $comment):Response
    {
        $form = $this->createForm(AdminCommentEditType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash('success', "Le commentaire numéro : {$comment->getId()} a bien été modififié 🤗");

            return $this->redirectToRoute('admin_comments_list');
        }

        return $this->render('admin/comment/admin_comment_edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment
        ]);
    }

    /**
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function adminCommentDelete(Comment $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash('success', "❌ Le commentaire de : {$comment->getAuthor()->getFullName()} a bien été supprimé !");

        return $this->redirectToRoute('admin_comments_list');

    }


}
