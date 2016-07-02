<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.06.2016
 * Time: 19:30
 */

namespace BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\Article;
use BlogBundle\Form\ArticleForm;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    public function showAction($id)
    {
        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        }

        if (!$article = $this->getDoctrine()
            ->getRepository('BlogBundle:Article')
            ->findOneWithUserLike($id, $userId)) {
            throw $this->createNotFoundException('Article not found');
        }

        $comments = $article->getCommentsByParents();

        return $this->render('BlogBundle::pages/show.html.twig', array(
            'article' => $article,
            'comments' => $comments,
        ));
    }

    public function newAction()
    {
        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);

        return $this->render('BlogBundle::forms/article.html.twig', array(
            'article' => $article,
            'form'   => $form->createView()
        ));
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        } else {
            $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        }

        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isValid() && !empty($article->getContentFiles()) && !empty($article->getContentTexts())) {
            $article->setUser($this->getUser());
            $article->setUserId($userId);

            $em->persist($article);
            $em->flush();

            return $this->redirect($this->generateUrl('BlogBundle_show', ['id' => $article->getId()]));
        }

        return $this->render('BlogBundle::forms/article.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }

}