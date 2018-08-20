<?php

/**
 * This file is a controller which is responsible for all of the product actions
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ImagesActions;

/**
 * @Route("/product")
 * Class ProductController
 * @package App\Controller
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_index", methods="GET")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', ['products' => $productRepository->findAll()]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     * @param Request $request
     * @param ImagesActions $imagesActionsService
     * @return Response
     */
    public function new(Request $request, ImagesActions $imagesActionsService): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage($form->get('imageFile')->getData());
                $product->setMainImage($mainImage);
            }

            if(!is_null($form->get('imageFiles')->getData())){
                $product->addImages($imagesActionsService->createImagesCollection($form->get('imageFiles')->getData()));
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'New product has been added.'
            );

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     * @param Request $request
     * @param Product $product
     * @param ImagesActions $imagesActionsService
     * @return Response
     */
    public function edit(Request $request, Product $product, ImagesActions $imagesActionsService): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            if(!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage($form->get('imageFile')->getData());
                $product->setMainImage($mainImage);
            }

            if(!is_null($form->get('imageFiles')->getData())){
                $product->addImages($imagesActionsService->createImagesCollection($form->get('imageFiles')->getData()));
            }

            $em->flush();

            $this->addFlash(
                'notice',
                'Edited successfully.'
            );

            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'Deleted successfully.'
            );
        }

        return $this->redirectToRoute('product_index');
    }
}