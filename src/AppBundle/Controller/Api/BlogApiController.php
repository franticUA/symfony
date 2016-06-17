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
        $vote->setEntity($em);

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
}