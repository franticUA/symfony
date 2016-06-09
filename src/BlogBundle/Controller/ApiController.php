<?php

namespace BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\ArticleLikes;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    public function articleAction($id, $type = 1)
    {
        $message = '';
        $code = 200;
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $code = 400;
            $message = 'ACCESS_DENIED';
        }elseif ($id){
            $em = $this->getDoctrine()->getManager();

            $user_id = $this->getUser()->getId();

            if($like = $em->getRepository('BlogBundle:ArticleLikes')
                ->findOneBy(['articleId' => $id, 'userId' => $user_id])
            ){
                $message = 'update';
                $like->setVal(1*$type);
            }else{
                $articleLike = new ArticleLikes();
                $articleLike->setUserId($user_id);
                $articleLike->setArticleId($id);
                $articleLike->setVal(1*$type);
                $em->persist($articleLike);
                $message = 'add';
            }
            $em->flush();
        }

        return JsonResponse::create([ 'message' => $message ], $code);
    }
}