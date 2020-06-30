<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation as Doc;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $session = $this->get('session');
        $cartElements = $session->get('cartElements');
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'currentPage' => 'Nouveautés',
            'cart' => $cartElements
        ]);
    }

    /**
     * @Route("/promotion", name="article_promotion", methods={"GET"})
     */
    public function promotion(ArticleRepository $articleRepository): Response
    {
        $session = $this->get('session');
        $cartElements = $session->get('cartElements');
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'currentPage' => 'Promotions',
            'cart' => $cartElements
        ]);
    }

    /**
     * @Route("/categorie/{category}", name="article_categorie", methods={"GET"})
     */
    public function categorie($category)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([
            'categorie' => $category
        ]);
        $session = $this->get('session');
        $cartElements = $session->get('cartElements');
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'currentPage' => 'Categorie',
            'currentCategory' => $category,
            'cart' => $cartElements
        ]);
    }

    /**
     * @Route("/api/getPromo", name="apiGetPromo", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne une liste de 5 articles en promo",
     *
     * )
     * @SWG\Tag(name="Article")
     */
    public function apiGetPromo()
    {

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAllPromo();

        $arrayCollection = [];

        /** @var Article $item */
        foreach($articles as $item) {


            $arrayCollection[] = array(
                'id' => $item->getId(),
                'nom' => $item->getNom(),
                'categorie' => $item->getCategorie(),
                'description' => $item->getDescription(),
                'caracteristiques' => $item->getCaracteristiques(),
                'prix_unitaire' => $item->getPrixUnitaire(),
                'poids' => $item->getPoids(),
                'qte_en_stock' => $item->getQteEnStock(),
                'is_new' => $item->getIsNew(),
                'promotion' => $item->getPromotion(),
                'image' => $item->getImages()->first()->getLien(),
            );
        }
        return new JsonResponse($arrayCollection);
    }


    /**
     * @Route("/api/getNew", name="apiGetNew", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne une liste de 5 nouveaux articles",
     *
     * )
     * @SWG\Tag(name="Article")
     */
    public function apiGetNew()
    {

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAllNew();

        $arrayCollection = [];

        /** @var Article $item */
        foreach($articles as $item) {


            $arrayCollection[] = array(
                'id' => $item->getId(),
                'nom' => $item->getNom(),
                'categorie' => $item->getCategorie(),
                'description' => $item->getDescription(),
                'caracteristiques' => $item->getCaracteristiques(),
                'prix_unitaire' => $item->getPrixUnitaire(),
                'poids' => $item->getPoids(),
                'qte_en_stock' => $item->getQteEnStock(),
                'is_new' => $item->getIsNew(),
                'promotion' => $item->getPromotion(),
                'image' => $item->getImages()->first()->getLien(),
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/getAllArticles", name="apiGetAllArticles", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne une liste de tous les articles",
     *
     * )
     * @SWG\Tag(name="Article")
     */
    public function apiGetAllArticles()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $arrayCollection = [];

        $firstimage = "";

        /** @var Article $item */
        foreach($articles as $item) {


            $arrayCollection[] = array(
                'id' => $item->getId(),
                'nom' => $item->getNom(),
                'categorie' => $item->getCategorie(),
                'description' => $item->getDescription(),
                'caracteristiques' => $item->getCaracteristiques(),
                'prix_unitaire' => $item->getPrixUnitaire(),
                'poids' => $item->getPoids(),
                'qte_en_stock' => $item->getQteEnStock(),
                'is_new' => $item->getIsNew(),
                'promotion' => $item->getPromotion(),
                'image' => $item->getImages()->first()->getLien(),
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/getCategories", name="apiGetCategories", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne une liste de toutes les catégories",
     *
     * )
     * @SWG\Tag(name="Article")
     */
    public function apiGetCategories()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $arrayCollection = [];

        /** @var Article $item */
        foreach($articles as $item) {
            if(!in_array($item->getCategorie(), $arrayCollection)) {
                $arrayCollection[] = $item->getCategorie();
            }
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/searchByterm", name="apiSearchArticle", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne une liste de tous les articles",
     *
     * )
     * @SWG\Tag(name="Article")
     * @SWG\Parameter(
     *     name="term",
     *     in="query",
     *     type="string",
     *     description="Le nom de l'article recherché"
     * )
     */
    public function searchByTerm(Request $response)
    {
        $content = $_POST;
        $term =  $response->get('term');

        /** @var Article $articles */
        $articles = $this->getDoctrine()->getRepository(Article::class)->findByTerm($term);

        $arrayCollection = [];


        /** @var Article $item */
        foreach($articles as $item) {


            $arrayCollection[] = array(
                'id' => $item->getId(),
                'nom' => $item->getNom(),
                'categorie' => $item->getCategorie(),
                'description' => $item->getDescription(),
                'caracteristiques' => $item->getCaracteristiques(),
                'prix_unitaire' => $item->getPrixUnitaire(),
                'poids' => $item->getPoids(),
                'qte_en_stock' => $item->getQteEnStock(),
                'is_new' => $item->getIsNew(),
                'promotion' => $item->getPromotion(),
                'image' => $item->getImages()->first()->getLien(),
            );
        }

        return new JsonResponse([
            'articles' => $arrayCollection
        ]);
    }

    /**
     * @Route("/api/getArticle/{id}", name="apiGetArticle", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne l'article trouvé par id",
     *
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Retourne une erreur 404 article_not_found",
     *
     * )
     * @SWG\Tag(name="Article")
     */
    public function apiGetArticle($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if(!$article) {
            return new JsonResponse(array(
                'status' => 'article_not_found',
            ), 404);
        }

        $imagesCollection = [];

        foreach ($article->getImages() as $image)
        {
            $imagesCollection[] = $image->getLien();
        }

        $arrayCollection[] = array(
            'id' => $article->getId(),
            'nom' => $article->getNom(),
            'categorie' => $article->getCategorie(),
            'description' => $article->getDescription(),
            'caracteristiques' => $article->getCaracteristiques(),
            'prix_unitaire' => $article->getPrixUnitaire(),
            'images' => $imagesCollection,
            'image' => $article->getImages()->first()->getLien(),
            'poids' => $article->getPoids(),
            'qte_en_stock' => $article->getQteEnStock(),
            'is_new' => $article->getIsNew(),
            'promotion' => $article->getPromotion()
        );

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/createArticle", name="apiCreateArticle", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne l'article nouvellement créé",
     *
     * )
     * @SWG\Parameter(
     *     name="nom",
     *     in="query",
     *     type="string",
     *     description="Nom de l'article"
     * )
     * @SWG\Parameter(
     *     name="poids",
     *     in="query",
     *     type="integer",
     *     description="Poids de l'article"
     * )
     * @SWG\Parameter(
     *     name="prix",
     *     in="query",
     *     type="integer",
     *     description="Prix unitaire de l'article"
     * )
     * @SWG\Parameter(
     *     name="description",
     *     in="query",
     *     type="string",
     *     description="Description de l'article"
     * )
     * @SWG\Parameter(
     *     name="categorie",
     *     in="query",
     *     type="string",
     *     description="Categorie de l'article"
     * )
     * @SWG\Parameter(
     *     name="caracteristiques",
     *     in="query",
     *     type="string",
     *     description="Caracteristiques de l'article"
     * )
     *
     * @SWG\Parameter(
     *     name="qty",
     *     in="query",
     *     type="integer",
     *     description="Quantité en stock de l'article"
     * )
     *
     * @SWG\Parameter(
     *     name="isNew",
     *     in="query",
     *     type="boolean",
     *     description="Nouveauté ou non de l'article"
     * )
     *
     * @SWG\Parameter(
     *     name="promotion",
     *     in="query",
     *     type="integer",
     *     description="Promotion en pourcentage de l'article"
     * )
     *
     * @SWG\Tag(name="Article")
     */
    public function apiCreateArticle(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setNom($request->get('nom'));
        $article->setPoids($request->get('poids'));
        $article->setPrixUnitaire($request->get('prix'));
        $article->setDescription($request->get('description'));
        $article->setCategorie($request->get('categorie'));
        $article->setCaracteristiques($request->get('caracteristiques'));
        $article->setQteEnStock($request->get('qty'));
        $article->setIsNew($request->get('isNew'));
        $article->setPromotion($request->get('promotion'));

        $arrayCollection[] = array(
            'id' => $article->getId(),
            'nom' => $article->getNom(),
            'categorie' => $article->getCategorie(),
            'description' => $article->getDescription(),
            'caracteristiques' => $article->getCaracteristiques(),
            'prix_unitaire' => $article->getPrixUnitaire(),
            'poids' => $article->getPoids(),
            'qte_en_stock' => $article->getQteEnStock(),
            'is_new' => $article->getIsNew(),
            'promotion' => $article->getPromotion()
        );

        $entityManager->persist($article);

        $entityManager->flush();

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/manageArticle/{id}", name="apiManageArticle", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne l'article modifié",
     *
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="Retourne une erreur 404 article_not_found",
     *
     * )
     *
     * @SWG\Parameter(
     *     name="nom",
     *     in="query",
     *     type="string",
     *     description="Nom de l'article"
     * )
     * @SWG\Parameter(
     *     name="poids",
     *     in="query",
     *     type="integer",
     *     description="Poids de l'article"
     * )
     * @SWG\Parameter(
     *     name="prix",
     *     in="query",
     *     type="integer",
     *     description="Prix unitaire de l'article"
     * )
     * @SWG\Parameter(
     *     name="description",
     *     in="query",
     *     type="string",
     *     description="Description de l'article"
     * )
     * @SWG\Parameter(
     *     name="categorie",
     *     in="query",
     *     type="string",
     *     description="Categorie de l'article"
     * )
     * @SWG\Parameter(
     *     name="caracteristiques",
     *     in="query",
     *     type="string",
     *     description="Caracteristiques de l'article"
     * )
     *
     * @SWG\Parameter(
     *     name="qty",
     *     in="query",
     *     type="integer",
     *     description="Quantité en stock de l'article"
     * )
     *
     * @SWG\Parameter(
     *     name="isNew",
     *     in="query",
     *     type="boolean",
     *     description="Nouveauté ou non de l'article"
     * )
     *
     * @SWG\Parameter(
     *     name="promotion",
     *     in="query",
     *     type="integer",
     *     description="Promotion en pourcentage de l'article"
     * )
     *
     * @SWG\Tag(name="Article")
     */
    public function apiManageArticle(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var Article $article */
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if(!$article) {
            return new JsonResponse(array(
                'status' => 'article_not_found',
            ), 404);
        }


        $article->setNom($request->get('nom')=== null ? $article->getNom() : $request->get('nom'));
        $article->setPoids($request->get('poids') === null ? $article->getPoids() : $request->get('poids'));
        $article->setPrixUnitaire($request->get('prix') === null ? $article->getPrixUnitaire() : $request->get('prix'));
        $article->setDescription($request->get('description') === null ? $article->getDescription() : $request->get('description'));
        $article->setCategorie($request->get('categorie') === null ? $article->getCategorie() : $request->get('categorie'));
        $article->setCaracteristiques($request->get('caracteristiques') === null ? $article->getCaracteristiques() : $request->get('caracteristiques'));
        $article->setQteEnStock($request->get('qty') === null ? $article->getQteEnStock() : $request->get('qty') );
        $article->setIsNew($request->get('isNew') === null ? $article->getIsNew() : $request->get('isNew'));
        $article->setPromotion($request->get('promotion') === null ? $article->getPromotion() : $request->get('promotion'));

        $arrayCollection[] = array(
            'id' => $article->getId(),
            'nom' => $article->getNom(),
            'categorie' => $article->getCategorie(),
            'description' => $article->getDescription(),
            'caracteristiques' => $article->getCaracteristiques(),
            'prix_unitaire' => $article->getPrixUnitaire(),
            'poids' => $article->getPoids(),
            'qte_en_stock' => $article->getQteEnStock(),
            'is_new' => $article->getIsNew(),
            'promotion' => $article->getPromotion()
        );

        $entityManager->persist($article);

        $entityManager->flush();

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/deleteArticle/{id}", name="apiDeleteArticle", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Retourne deletion_success",
     *
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Retourne une erreur 404 article_not_found",
     *
     * )
     * @SWG\Tag(name="Article")
     */
    public function apiDeleteArticle($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if(!$article) {
            return new JsonResponse(array(
                'status' => 'article_not_found',
            ), 404);
        }

        $entityManager->remove($article);

        $entityManager->flush();

        return new JsonResponse(array("deletion" => "success"));
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article, [
            'action' => $this->generateUrl('article_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        $session = $this->get('session');
        $cartElements = $session->get('cartElements');
        return $this->render('article/show.html.twig', [
            'article' => $article->getId(),
            'cart' => $cartElements
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
