<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// use Doctrine\Common\Persistance\ObjectManager;



    class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="app_article")
     */
    public function index(ArticleRepository $articlesRepository): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        //$articles => $repo;

        return $this->render('article/index.html.twig', [

            'articles' => $articlesRepository->findAll(),
        ]);
    }


    /**
     * @Route("/article/17", name="app_article_affichage")
     * 
     */
    public function affichage()
    {    
        return $this->render('article/affichage.html.twig', [

        ]);

    }

    /**
     * @Route("/catalogue", name="app_catalogue")
     */
    public function catalogue(): Response
    {
        return $this->render('article/catalogue.html.twig', [

        ]);
    }
    /**
     * @Route("/abonnements", name="app_abonnements")
     */
    public function abonnements(): Response
    {
        return $this->render('article/abonnement.html.twig', [

        ]);
    }

}
