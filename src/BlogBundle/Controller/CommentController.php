<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\Comments;
use BlogBundle\Form\CommentsForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($id)
    {
        $article = $this->getArticle($id);

        $comment = new Comments();
        $comment->setArticle($article);
        $comment->setArticleId($id);
        $form   = $this->createForm(CommentsForm::class, $comment);

        return $this->render('BlogBundle::forms/comment.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction(Request $request, $id)
    {
        $article = $this->getArticle($id);

        $comment  = new Comments();
        $comment->setArticle($article);
        $comment->setArticleId($id);
        $form    = $this->createForm(CommentsForm::class, $comment);
        $form->handleRequest($request);

        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        } else {
            $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        }

        $post = '';
        if ($form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setUserId($userId);
            $comment->setArticleId($id);
            $comment->setArticle($article);
            if (!$comment->getParentId()) {
                $comment->setParentId(0);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $post = '#comment-' . $comment->getId();
        }

        return $this->redirect($this->generateUrl('BlogBundle_show', array(
                'id' => $comment->getArticleId())) .
                $post
            );
    }

    protected function getArticle($id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blog = $em->getRepository('BlogBundle:Article')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $blog;
    }
}