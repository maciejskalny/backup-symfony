<?php

/**
 * This file is a controller which is responsible for all of the product actions
 *
 * PHP version 7.1.16
 *
 * @category  Controller
 * @package   Virtua_Internship
 * @author    Maciej Skalny <contact@wearevirtua.com>
 * @copyright 2018 Copyright (c) Virtua (http://wwww.wearevirtua.com)
 * @license   GPL http://opensource.org/licenses/gpl-license.php
 * @link      https://github.com/maciejskalny/backup-symfony
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
 * Class ProductController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 *
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * Shows all products
     *
     * @param ProductRepository $productRepository
     *
     * @Route("/", name="product_index", methods="GET")
     *
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render(
            'product/index.html.twig',
            ['products' => $productRepository->findAll()]
        );
    }

    /**
     * Creates new product
     *
     * @param Request       $request
     * @param ImagesActions $imagesActionsService
     *
     * @Route("/new", name="product_new", methods="GET|POST")
     *
     * @return Response
     */
    public function new(
        Request $request,
        ImagesActions $imagesActionsService
    ): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage(
                    $form->get('imageFile')->getData()
                );
                $product->setMainImage($mainImage);
            }

            if (!is_null($form->get('imageFiles')->getData())) {
                $product->addImages(
                    $imagesActionsService->createImagesCollection(
                        $form->get('imageFiles')->getData()
                    )
                );
            }

            $em->persist($product);
            $em->flush();

            $this->addFlash(
                'notice',
                'New product has been added.'
            );

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/new.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * Shows one product
     *
     * @param Product $product
     *
     * @Route("/{id}", name="product_show", methods="GET")
     *
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * Updates product
     *
     * @param Request       $request
     * @param Product       $product
     * @param ImagesActions $imagesActionsService
     *
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     *
     * @return Response
     */
    public function edit(
        Request $request,
        Product $product,
        ImagesActions $imagesActionsService
    ): Response {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage(
                    $form->get('imageFile')->getData()
                );
                $product->setMainImage($mainImage);
            }

            if (!is_null($form->get('imageFiles')->getData())) {
                $product->addImages(
                    $imagesActionsService->createImagesCollection(
                        $form->get('imageFiles')->getData()
                    )
                );
            }

            $em->flush();

            $this->addFlash(
                'notice',
                'Edited successfully.'
            );

            return $this->redirectToRoute(
                'product_edit',
                ['id' => $product->getId()]
            );
        }

        return $this->render(
            'product/edit.html.twig',
            [
                'product' => $product,
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * Removes product
     *
     * @param Request $request
     * @param Product $product
     *
     * @Route("/{id}", name="product_delete", methods="DELETE")
     *
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid(
            'delete'.$product->getId(),
            $request->request->get('_token')
        )
        ) {
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
