<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Rating;
use App\Form\ArticleType;
use App\Form\RatingType;
use App\Repository\ArticleRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;




class ArticleController extends AbstractController
{
    #[Route('/article', name: 'article.index')]
    #[IsGranted('ROLE_USER')]
    public function index( ArticleRepository $repository): Response
    {
        $article = $repository->findAll();

        return $this->render('pages/article/index.html.twig', [
            'articles' => $article,
        ]);
    }

    #[Route('/article/creation', name:'article.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setUser($this->getUser());
            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success','Votre article a bien été créé');

            return $this->redirectToRoute('article.index');
        }

        return $this->render('pages/article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/edition/{id}', name:'article.edit', methods: ['GET', 'POST'])]
    #[IsGranted(
        attribute: new Expression('is_granted("ROLE_USER") and user === subject.getUser()'),
        subject: 'article',
    )]
    public function edit(Article $article, Request $request, EntityManagerInterface $manager) : Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success', 'Votre article a bien été modifié');
            return $this->redirectToRoute('article.index');
        }

        return $this->render('pages/article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/suppression/{id}', name:'article.delete', methods: ['GET', 'POST'])]
    #[IsGranted(
        new Expression('is_granted("ROLE_USER") and user === subject.getUser()'),
        subject: 'article',
    )]
    public function delete(Article $article, EntityManagerInterface $manager, Request $request) : Response
    {
        if(!$article){
            $this->addFlash('error','L\'article n\'existe pas');
            return $this->redirectToRoute('article.index');
        }

        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success','L\'article a bien été supprimé');
        return $this->redirectToRoute('article.index');
    }


    #[Route('/article/{id}', name:'article.show', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function show(Article $article, Request $request, EntityManagerInterface $manager, RatingRepository $ratingRepository) : Response
    {
        $rating = new Rating();
        $form = $this->createForm(RatingType::class, $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setUser($this->getUser())
                ->setArticle($article);

            $existingRating = $ratingRepository->findOneBy([
                'user' => $this->getUser(),
                'article' => $article,
            ]);
            if ($existingRating) {
                $this->addFlash('error', 'Vous avez déjà noté cet article.');
                return $this->redirectToRoute('article.show', ['id' => $article->getId()]);
            }

            if(!$existingRating){
                $manager->persist($rating);
            } else {
                $existingRating->setRate($rating->getRate());
            }

            $manager->flush();

            $this->addFlash('success','Votre note a bien été enregistrée');
            return $this->redirectToRoute('article.show', ['id' => $article->getId()]);
        }

        return $this->render('pages/article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
