<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/save", name="save_article", methods={"GET", "POST"})
     */
    public function save(): Response
    {
        $article = new Article();
        $article->setNom('Article 3');
        $article->setPrix(4000);

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function showArticles(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('articles/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/article/edit/{id}", name="edit_article", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/article/delete/{id}", name="delete_article", methods={"POST"})
     */
    public function delete(Request $request, $id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        $entityManager = $this->entityManager;
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('home'); // Rediriger vers la page d'accueil ou une autre page après la suppression
    }

    /**
     * @Route("/article/{id}", name="article_details")
     */
    public function details($id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('articles/show.html.twig', ['article' => $article]);
    }

    /**
 * @Route("/category/new", name="new_category", methods={"GET", "POST"})
 */

    public function newCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();
        }
        
        return $this->render('category/NewCategory.html.twig', ['form' => $form->createView()]);

    }
}
