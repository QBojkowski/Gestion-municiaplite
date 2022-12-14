<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Commentaire;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_article", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request, ArticleRepository $articleRepository): Response
    {
        $donnees = $articleRepository->findAll();

        $articles = $paginator->paginate(
            $donnees, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
    );
    
    return $this->render('article/index.html.twig', [
           'articles' => $articles,
        ]);
    }

    /**
     * @Route("/new", name="app_articles_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArticleRepository $articleRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->add($article, true);

            return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_article_affichage", methods={"GET"})
     */
    public function affichage(EntityManagerInterface $entityManager, Article $articles, Request $request, $id)
    {    
        $commentaire = new Commentaire();
        //$commentairesForm = $this->createForm(CommentairesType::class, $commentaires);

        $form = $this->createFormBuilder($commentaire)
                ->add('auteur')
                ->add('contenu') 
                ->getForm();
       
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $commentaire->setCreatedAt(new \DateTime())
                     ->setArticle($articles);
            
            $entityManager->persist($commentaire);

            $entityManager->flush();

            return $this->redirectToRoute('app_article_affichage',  ['id' => $articles->getId()
            ]);
    }

        return $this->render('article/affichage.html.twig', [

        'articles'=>$articles,
        'form'=> $form->createView()
        //'commentaires '=> $commentaires ,

        ]);

    }



    /**
     * @Route("/{id}/edit", name="app_article_edit", methods={"GET", "POST"})
     */
    public function editer(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->add($article, true);

            return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_articles_suppr", methods={"POST"})
     */
    public function supprimer(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article', [], Response::HTTP_SEE_OTHER);
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
