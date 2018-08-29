<?php

/**
 * This file is a controller which is responsible for all product category actions
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

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Form\ImageType;
use App\Form\ProductCategoryType;
use App\Form\ProductPaginationType;
use App\Repository\ProductCategoryRepository;
use App\Service\ImagesActions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductCategoryController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 *
 * @Route("/category")
 */
class ProductCategoryController extends Controller
{
    /**
     * Shows all categories
     *
     * @param ProductCategoryRepository $productCategoryRepository
     *
     * @Route("/", name="product_category_index", methods="GET")
     *
     * @return Response
     */
    public function index(
        ProductCategoryRepository $productCategoryRepository
    ): Response {
        return $this->render(
            'product_category/index.html.twig',
            ['product_categories' => $productCategoryRepository->findAll()]
        );
    }

    /**
     * Creates new category
     *
     * @param Request       $request
     * @param ImagesActions $imagesActionsService
     *
     * @Route("/new", name="product_category_new", methods="GET|POST")
     *
     * @return Response
     */
    public function new(
        Request $request,
        ImagesActions $imagesActionsService
    ): Response {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage(
                    $form->get('imageFile')->getData()
                );

                $productCategory->setMainImage($mainImage);
            }

            if (!is_null($form->get('imageFiles')->getData())) {
                $productCategory->addImages(
                    $imagesActionsService->createImagesCollection(
                        $form->get('imageFiles')->getData()
                    )
                );
            }

            $em->persist($productCategory);
            $em->flush();

            $this->addFlash(
                'notice',
                'New category has been added.'
            );

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render(
            'product_category/new.html.twig',
            [
                'product_category' => $productCategory,
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * Shows one category
     *
     * @param ProductCategory $productCategory
     * @param Request         $request
     *
     * @Route("/{id}", name="product_category_show", methods="GET|POST")
     *
     * @return Response
     */
    public function show(
        ProductCategory $productCategory,
        Request $request
    ): Response {
        $form = $this->createForm(ProductPaginationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = $form->get('productCount')->getData();
        } else {
            $count = 6;
        }

        $products = $productCategory->getProducts();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            $count
        );
        return $this->render(
            'product_category/show.html.twig',
            [
                'product_category' => $productCategory,
                'pagination' => $pagination,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Updates category
     *
     * @param Request         $request
     * @param ProductCategory $productCategory
     * @param ImagesActions   $imagesActionsService
     *
     * @Route("/{id}/edit", name="product_category_edit", methods="GET|POST")
     *
     * @return Response
     */
    public function edit(
        Request $request,
        ProductCategory $productCategory,
        ImagesActions $imagesActionsService
    ): Response {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage(
                    $form->get('imageFile')->getData()
                );
                $productCategory->setMainImage($mainImage);
            }

            if (!is_null($form->get('imageFiles')->getData())) {
                $productCategory->addImages(
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
                'product_category_edit',
                ['id' => $productCategory->getId()]
            );
        }

        return $this->render(
            'product_category/edit.html.twig',
            [
                'product_category' => $productCategory,
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * Removes category
     *
     * @param Request         $request
     * @param ProductCategory $productCategory
     *
     * @Route("/{id}", name="product_category_delete", methods="DELETE")
     *
     * @return Response
     */
    public function delete(
        Request $request,
        ProductCategory $productCategory
    ): Response {
        if ($this->isCsrfTokenValid(
            'delete'.$productCategory->getId(),
            $request->request->get('_token')
        )
        ) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($productCategory);
            $em->flush();

            $this->addFlash(
                'notice',
                'Deleted successfully.'
            );
        }

        return $this->redirectToRoute('product_category_index');
    }
}
