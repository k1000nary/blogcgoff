<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('blog/home.html.twig', [

        ]);
    }
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repository, CategorieRepository $category)
    {
        $articles = $repository->findBy(
            ['publication' => true],
            ['date' => 'DESC']
        );
        $categories = $category->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'cat'=> $categories,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_show")
     */
    public function show(Article $article, Request $request, ObjectManager $manager, CategorieRepository $category)
    {
        $categories = $category->findAll();
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setDate(new \DateTime())
                ->setArticle($article)
                ->setPublication(false);
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Votre commentaire à bien été enregistré.');
            return $this->redirectToRoute('blog_show', [
                'slug'=>$article->getSlug(),
            ]);
        }
        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'formComment'=>$form->createView(),
            'cat'=> $categories,
        ]);
    }

    /**
     * @Route("/blog/categorie/{nom}", name="show_cat")
     */
    public function showCat(Categorie $categorie, ArticleRepository $repository, CategorieRepository $category)
    {
        $articles = $repository->findByCatagories($categorie->getId());
        $categories = $category->findAll();
        return $this->render('blog/categorie.html.twig', [
            'articles' => $articles,
            'categorie'=> $categorie,
            'cat'=> $categories,
        ]);
    }
}
