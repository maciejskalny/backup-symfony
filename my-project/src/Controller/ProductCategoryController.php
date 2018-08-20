<?php

/**
 * This file is a controller which is responsible for all of the product category actions
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Controller;

use App\Entity\Image;
use App\Entity\ProductCategory;
use App\Form\ImageType;
use App\Form\ProductCategoryType;
use App\Repository\ProductCategoryRepository;
use App\Service\ImagesActions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 * Class ProductCategoryController
 * @package App\Controller
 */
class ProductCategoryController extends Controller
{
    /**
     * @Route("/", name="product_category_index", methods="GET")
     * @param ProductCategoryRepository $productCategoryRepository
     * @return Response
     */
    public function index(ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render('product_category/index.html.twig', ['product_categories' => $productCategoryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="product_category_new", methods="GET|POST")
     * @param Request $request
     * @param ImagesActions $imagesActionsService
     * @return Response
     */
    public function new(Request $request, ImagesActions $imagesActionsService): Response
    {
        $productCategory = new ProductCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage($form->get('imageFile')->getData());
                $productCategory->setMainImage($mainImage);
            }

            if(!is_null($form->get('imageFiles')->getData())){
                $productCategory->addImages($imagesActionsService->createImagesCollection($form->get('imageFiles')->getData()));
            }

            $em->persist($productCategory);
            $em->flush();

            $this->addFlash(
                'notice',
                'New category has been added.'
            );

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render('product_category/new.html.twig', [
            'product_category' => $productCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_category_show", methods="GET")
     * @param ProductCategory $productCategory
     * @return Response
     */
    public function show(ProductCategory $productCategory): Response
    {
        return $this->render('product_category/show.html.twig', ['product_category' => $productCategory]);
    }

    /**
     * @Route("/{id}/edit", name="product_category_edit", methods="GET|POST")
     * @param Request $request
     * @param ProductCategory $productCategory
     * @param ImagesActions $imagesActionsService
     * @return Response
     */
    public function edit(Request $request, ProductCategory $productCategory, ImagesActions $imagesActionsService): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(!is_null($form->get('imageFile')->getData())) {
                $mainImage = $imagesActionsService->createImage($form->get('imageFile')->getData());
                $productCategory->setMainImage($mainImage);
            }

            if(!is_null($form->get('imageFiles')->getData())){
                $productCategory->addImages($imagesActionsService->createImagesCollection($form->get('imageFiles')->getData()));
            }

            $em->flush();

            $this->addFlash(
                'notice',
                'Edited successfully.'
            );

            return $this->redirectToRoute('product_category_edit', ['id' => $productCategory->getId()]);
        }

        return $this->render('product_category/edit.html.twig', [
            'product_category' => $productCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_category_delete", methods="DELETE")
     * @param Request $request
     * @param ProductCategory $productCategory
     * @return Response
     */
    public function delete(Request $request, ProductCategory $productCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCategory->getId(), $request->request->get('_token'))) {
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