<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $session = $this->get('session');

        $cartElements = $session->get('cartElements');
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/Article", name="Article")
     */
    public function Article()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'controller_name' => 'HomeController',
            'cart' => $cartElements,
        ]);
    }
}
