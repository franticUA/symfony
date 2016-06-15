<?php

namespace BlogBundle\Controller;


use BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogController extends Controller
{

    public function listAction($page)
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
            ->select('b')
            ->addSelect('l')
            ->from('BlogBundle:Article',  'b')
            ->leftJoin('b.likes', 'l', 'WITH', 'b.id=l.articleId AND l.userId=:userId')
            ->setParameter('userId', $userId)
            ->addOrderBy('b.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $articles = new Paginator($query, $fetchJoinCollection = true);

        $all_count = $articles->count();
        $pages = ceil($all_count/$limit);

        $paginator = ['page' => $page, 'pages' => $pages, 'start' => 1, 'end' => $pages];
        $paginator['start'] = ($page > 5) ? $page - 3 : 1;
        $paginator['end'] = ($page + 5 < $pages) ? $page + 3 : $pages;

        return $this->render('BlogBundle::pages/main.html.twig', array(
            'articles' => $articles,
            'paginator' => $paginator
        ));
    }

    public function showAction($id)
    {
        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $userId = $this->getUser()->getId();
        }

        $article = $this->getDoctrine()
            ->getRepository('BlogBundle:Article')
            ->findOneWithUserLike($id, $userId);

        $comments = $article->getCommentsByParents();
//dump($comments);die();
        return $this->render('BlogBundle::pages/show.html.twig', array(
            'article' => $article,
            'comments' => $comments,
        ));
    }

    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $user_id = $this->getUser()->getId();

        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('content', TextareaType::class, array('attr' => array('class' => 'form-control')))
            ->add('add', SubmitType::class, array('label' => 'Add', 'attr' => array('class' => 'btn')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $article->setUserId($user_id);
            $article->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('BlogBundle_show', ['id' => $article->getId()]);
        }

        return $this->render('BlogBundle::pages/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}