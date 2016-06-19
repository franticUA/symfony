<?php

namespace BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BlogController extends Controller
{

    public function listAction($page)
    {
        $parameters = $this->blogList($page);

        return $this->render('BlogBundle::pages/main.html.twig', $parameters);
    }
    
    public function userAction($page, $username)
    {
        $userManager = $this->get('fos_user.user_manager');
        if (!$user = $userManager->findUserByUsername($username)) {
            throw $this->createNotFoundException('User not found');
        }
        $authorId = $user->getId();
        $parameters = $this->blogList($page, $authorId);
        $parameters['user'] = $user;
        
        return $this->render('BlogBundle::pages/user.html.twig', $parameters);
    }
    
    private function blogList($page, $authorId = 0)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $limit = 10;

        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')){
            $userId = $this->getUser()->getId();
        }

        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $query = $em->createQueryBuilder()
            ->select(['b', 'l', 't', 'f'])
            ->from('BlogBundle:Article',  'b')
            ->leftJoin('b.likes', 'l', 'WITH', 'b.id=l.articleId AND l.userId=:userId')
            ->leftJoin('b.contentTexts', 't')
            ->leftJoin('b.contentFiles', 'f')
            ->setParameter('userId', $userId)
            ->addOrderBy('b.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        if ($authorId) {
            $query = $query->where('b.userId=:authorId')
                ->setParameter('authorId', $authorId);
        }

        $articles = new Paginator($query, $fetchJoinCollection = true);

        $all_count = $articles->count();
        $pages = ceil($all_count/$limit);

        $paginator = ['page' => $page, 'pages' => $pages, 'start' => 1, 'end' => $pages];
        $paginator['start'] = ($page > 5) ? $page - 3 : 1;
        $paginator['end'] = ($page + 5 < $pages) ? $page + 3 : $pages;

        return ['articles' => $articles, 'paginator' => $paginator];
    }
}