<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.06.2016
 * Time: 13:22
 */

namespace AppBundle\Controller\Api;


use AppBundle\Controller\ApiController;
use AppBundle\Api\BlogApi;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Api\Vote\CommentsVote;
use AppBundle\Api\Vote\ArticleVote;
use BlogBundle\Entity\Article;
use BlogBundle\Entity\Comments;
use BlogBundle\Form\ArticleForm;
use BlogBundle\Form\CommentsForm;

class BlogApiController extends ApiController
{

    public function commentAction($id, Request $request)
    {
        $vote = new CommentsVote($id);

        return $this->entity($vote, $request);
    }

    public function articleAction($id, Request $request)
    {
        $vote = new ArticleVote($id);

        return $this->entity($vote, $request);
    }
    
    public function entity(BlogApi $vote, Request $request)
    {
        $userId = 0;
        $action = $request->request->get('action');
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        } elseif ($action) {

            return $this->jsonResponse(null, 'ACCESS_DENIED', self::ERR_CODE);
        }

        $vote->setUserId($userId);
        
        if (!empty($action)){
            if (!isset(BlogApi::$types[$action])) {

                return $this->jsonResponse(null, 'UNKNOWN_ACTION', self::ERR_CODE);
            }
            $vote->setType(BlogApi::$types[$action]);
        }

        $em = $this->getDoctrine()->getManager();
        $vote->setEm($em);
        $vote->setEntity();

        $code = self::OK_CODE;
        $message = '';
        $data = null;

        if (empty($action)) {
            if ($entity = $vote->getEntity()) {
                $data = $entity->getArray();
            } else {
                $message = 'NOT_FOUND';
                $code = self::ERR_CODE;
            }
        } else {
            try {
                $vote->votedCheck($em);
                $result = $vote->liking($em);
                $message = $result['message'];
                $data = ['rating' => $result['rating']];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $code = self::ERR_CODE;
            }
        }

        return $this->jsonResponse($data, $message, $code);
    }

    public function createArticleAction(Request $request)
    {
        $data = null;
        $message = 'ok';
        $code = self::OK_CODE;

        $em = $this->getDoctrine()->getManager();
        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        } else {

            return $this->jsonResponse(null, 'ACCESS_DENIED', self::ERR_CODE);
        }

        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);
        if (empty($article->getContentFiles()) && empty($article->getContentTexts())) {
            $code = self::ERR_CODE;
            $message = 'Form invalid';
            $data = [['main' => 'Content Empty']];
        } elseif (!$form->isValid()) {
            $code = self::ERR_CODE;
            $message = 'Form invalid';
            $data = [];
            foreach ($form->getErrors(1) as $key => $error) {
                $data[$error->getCause()->getPropertyPath()] = $error->getCause()->getMessage();
            }
        } else {
            $article->setUser($this->getUser());
            $article->setUserId($userId);

            $em->persist($article);
            $em->flush();
            $data = ['redirectTo' => $this->generateUrl('BlogBundle_show', ['id' => $article->getId()])];
        }

        return $this->jsonResponse($data, $message, $code);
    }

    public function createCommentAction(Request $request, $id)
    {
        $data = null;
        $message = 'ok';
        $code = self::OK_CODE;

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        } else {

            return $this->jsonResponse(null, 'ACCESS_DENIED', self::ERR_CODE);
        }

        $em = $this->getDoctrine()->getManager();
        if (!$article = $em->getRepository('BlogBundle:Article')
           ->findOneBy(['id' => $id])
        ) {

            return $this->jsonResponse(null, 'NOT_FOUND', self::ERR_CODE);
        }

        $comment  = new Comments();
        $comment->setArticle($article);
        $comment->setArticleId($id);
        $form    = $this->createForm(CommentsForm::class, $comment);
        $form->handleRequest($request);

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
            $data = ['redirectTo' => $this->generateUrl('BlogBundle_show', ['id' => $article->getId()]).$post ];
        } else {
            $code = self::ERR_CODE;
            $message = 'Form invalid';
            $data = [];
            foreach ($form->getErrors(1) as $key => $error) {
                $data[$error->getCause()->getPropertyPath()] = $error->getCause()->getMessage();
            }
        }

        return $this->jsonResponse($data, $message, $code);
    }
}