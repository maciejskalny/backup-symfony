<?php

/**
 * This file is a controller which supports Category Rest api.
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

namespace App\Controller\Api;

use App\Entity\ProductCategory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Api\ApiProductCategoryType;
use App\Service\FormsActions;

/**
 * Class ApiProductCategoryController
 *
 * @category Class
 * @package  App\Controller\Api
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class ApiProductCategoryController extends Controller
{
    /**
     * Shows one category
     *
     * @param integer $id
     *
     * @Route("/api/category/{id}")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function showCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id' => $id]);
        if($category) {
            return new JsonResponse(json_encode($category->getCategoryInfo()));
        } else {
            return new JsonResponse('Not Found.', 404);
        }
    }

    /**
     * Shows all categories
     *
     * @Route("api/categories")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function showAllCategories()
    {
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAll();
        $data = ['categories' => []];
        foreach ($categories as $category){
            $data['categories'][] = $category->serializeCategory();
        }
        return new JsonResponse(json_encode($data), 200);
    }

    /**
     * Creates new category
     *
     * @param Request      $request
     * @param FormsActions $formsActionsService
     *
     * @Route("api/category")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function newCategory(Request $request, FormsActions $formsActionsService)
    {
            $category = new ProductCategory();
            $form = $this->createForm(ApiProductCategoryType::class, $category);
            $form->handleRequest($request);
            $form->submit($request->query->all());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
                return new JsonResponse('New category added.', 200);
            } else {
                return new JsonResponse('Bad request: '.json_encode($formsActionsService->showErrors($form)), 400);
            }
    }

    /**
     * Updates category
     *
     * @param Request      $request
     * @param FormsActions $formActionsService
     * @param integer      $id
     *
     * @Route("api/category/{id}/edit")
     * @Method("PUT")
     *
     * @return JsonResponse
     */
    public function editCategory(Request $request, FormsActions $formActionsService, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id'=>$id]);
        if($category) {
            $form = $this->createForm(ApiProductCategoryType::class, $category);
            $form->submit($request->query->all());
            if($form->isValid()) {
                $em->flush();
                return new JsonResponse('Category updated.', 200);
            } else {
                return new JsonResponse('Bad request: '.json_encode($formActionsService->showErrors($form)), 400);
            }
        } else {
            return new JsonResponse('Not found.', 404);
        }
    }

    /**
     * Removing category
     *
     * @param integer $id
     *
     * @Route("api/category/{id}/delete")
     * @Method("DELETE")
     *
     * @return JsonResponse
     */
    public function deleteCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id' => $id]);
        if($category) {
            $em->remove($category);
            $em->flush();
            return new JsonResponse('Category deleted.', 200);
        } else {
            return new JsonResponse('Not Found.', 404);
        }
    }
}