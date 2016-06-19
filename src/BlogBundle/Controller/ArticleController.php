<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.06.2016
 * Time: 19:30
 */

namespace BlogBundle\Controller;


use BlogBundle\Entity\ArticleFiles;
use BlogBundle\Entity\ArticleTexts;
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
        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        } else {
            $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        }

        $article  = new Article();
        $form     = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $article->setUser($this->getUser());
            $article->setUserId($userId);

            $content_empty = true;
            $contents = [];
            if ($contentTexts = $request->request->get('content')) {
                foreach ($contentTexts as $order => $text) {
                    $order = (int) $order;
                    if ($text) {
                        $articleText = new ArticleTexts();
                        $articleText->setContent($text);
                        $articleText->setSort($order);
                        $contents[] = $articleText;
                        $content_empty = false;
                    }
                }
            }

            if ($contentFiles = $request->files->get('content')) {
                foreach ($contentFiles as $order => $file) {
                    $order = (int) $order;
                    if ($file) {
                        $articleFile = new ArticleFiles();

                        $articleFile->setFile($file);
                        $articleFile->setSort($order);
                        $contents[] = $articleFile;
                        $content_empty = false;
                    }
                }
            }

            if (!$content_empty) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $articleId = $article->getId();

                foreach ($contents as $content) {
                    $content->setArticleId($articleId);
                    $content->setArticle($article);
                    if (get_class($content) == 'BlogBundle\Entity\ArticleFiles') {
                        $content->upload();
                    }
                    $em->persist($content);
                    $em->flush();
                }

                return $this->redirect($this->generateUrl('BlogBundle_show', array(
                    'id' => $article->getId()))
                );
            } else {

            }
        }

        return $this->redirect($this->generateUrl('BlogBundle_new'));
    }

}