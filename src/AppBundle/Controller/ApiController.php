<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Api\VoteApi;
use AppBundle\Api\Vote\CommentsVote;
use AppBundle\Api\Vote\ArticleVote;

class ApiController extends Controller
{
    const OK_CODE = 200;
    const ERR_CODE = 400;

    public function jsonResponse($data, $message, $code = self::OK_CODE) {
        return JsonResponse::create([
            'message' => $message,
            'data' => $data
        ], self::ERR_CODE);
    }

    public function voteAction(VoteApi $vote)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->jsonResponse(null, 'ACCESS_DENIED', self::ERR_CODE);
        }
        $userId = $this->getUser()->getId();

        $code = self::OK_CODE;
        try {
            $em = $this->getDoctrine()->getManager();
            $vote->votedCheck($userId, $em);
            $message = $vote->liking($em);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = self::ERR_CODE;
        }

        return $this->jsonResponse(null, $message, $code);
    }

    public function commentAction($id, $type = 1)
    {
        $vote = new CommentsVote($id, $type);

        return $this->voteAction($vote);
    }

    public function articleAction($id, $type = 1)
    {
        $vote = new ArticleVote($id, $type);

        return $this->voteAction($vote);
    }
}
