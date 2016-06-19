<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.05.2016
 * Time: 15:23
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/default")
     */
    public function indexAction()
    {
        $items = [
            [
                'id' => 1,
                'title' => 'Title',
                'text' => 'text'
            ],
            [
                'id' => 2,
                'title' => 'Title2',
                'text' => 'text2'
            ],
        ];

        return $this->render('AppBundle::pages/main.html.twig', [
            'items' => $items
        ]);
    }
    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }
}
