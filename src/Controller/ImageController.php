<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("/", name="image_index", methods={"GET"})
     */
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/api/getAllImages", name="apiGetAllImages", methods={"GET"})
     */
    public function apiGetAllImages()
    {
        $images = $this->getDoctrine()->getRepository(Image::class)->findAll();

        $arrayCollection = [];

        /** @var Image $item */
        foreach($images as $item) {
            $arrayCollection[] = array(
                'id' => $item->getId(),
                'lien' => $item->getLien(),
                'is_principale' => $item->getIsPrincipale(),
                'is_secondaire' => $item->getIsSecondaire(),
                'is_description' => $item->getIsDescription()
            );
        }
        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/getImage/{id}", name="apiGetImage", methods={"GET"})
     */
    public function apiGetImage($id)
    {
        $image = $this->getDoctrine()->getRepository(Image::class)->find($id);

        $arrayCollection[] = array(
            'id' => $image->getId(),
            'lien' => $image->getLien(),
            'is_principale' => $image->getIsPrincipale(),
            'is_secondaire' => $image->getIsSecondaire(),
            'is_description' => $image->getIsDescription()
        );

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/createImage", name="apiCreateImage", methods={"POST"})
     */
    public function apiCreateImage()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $image = new Image();
        $image->setLien('create');
        $image->setIsPrincipale(true);
        $image->setIsSecondaire(false);
        $image->setIsDescription(false);

        $arrayCollection[] = array(
            'id' => $image->getId(),
            'lien' => $image->getLien(),
            'is_principale' => $image->getIsPrincipale(),
            'is_secondaire' => $image->getIsSecondaire(),
            'is_description' => $image->getIsDescription()
        );

        $entityManager->persist($image);

        $entityManager->flush();

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/manageImage/{id}", name="apiManageImage", methods={"POST"})
     */
    public function apiManageImage($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $image = $this->getDoctrine()->getRepository(Image::class)->find($id);

        $image->setLien('modif');
        $image->setIsPrincipale(false);
        $image->setIsSecondaire(true);
        $image->setIsDescription(false);

        $arrayCollection[] = array(
            'id' => $image->getId(),
            'lien' => $image->getLien(),
            'is_principale' => $image->getIsPrincipale(),
            'is_secondaire' => $image->getIsSecondaire(),
            'is_description' => $image->getIsDescription()
        );

        $entityManager->persist($image);

        $entityManager->flush();

        return new JsonResponse($arrayCollection);
    }

    /**
     * @Route("/api/deleteImage/{id}", name="apiDeleteImage", methods={"POST"})
     */
    public function apiDeleteImage($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $image = $this->getDoctrine()->getRepository(Image::class)->find($id);

        $entityManager->remove($image);

        $entityManager->flush();

        return new JsonResponse(array("deletion" => "success"));
    }

    /**
     * @Route("/new", name="image_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('image_index');
        }

        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_show", methods={"GET"})
     */
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="image_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Image $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('image_index');
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="image_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Image $image): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('image_index');
    }
}
