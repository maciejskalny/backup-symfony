<?php
/**
 * This file is a controller which supports Category Rest api.
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
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

class ApiProductCategoryController extends Controller
{
    /**
     * @Route("/api/category/{id}")
     * @Method("GET")
     * @param integer $id
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
     * @Route("api/categories")
     * @Method("GET")
     * @return JsonResponse
     */
    public function showAllCategories()
    {
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAll();
        $data = array('categories' => array());
        foreach ($categories as $category){
            $data['categories'][] = $category->serializeCategory();
        }
        return new JsonResponse(json_encode($data), 200);
    }

    /**
     * @Route("api/category")
     * @Method("POST")
     * @param Request $request
     * @param FormsActions $formsActionsService
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
     * @Route("api/category/{id}/edit")
     * @Method("PUT")
     * @param Request $request
     * @param FormsActions $formActionsService
     * @param integer $id
     * @return JsonResponse
     */
    public function editCategory(Request $request, $id, FormsActions $formActionsService)
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
     * @Route("api/category/{id}/delete")
     * @Method("DELETE")
     * @param integer $id
     * @return JsonResponse
     */
    public function deleteCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id' => $id]);
        if($category){
            $em->remove($category);
            $em->flush();
            return new JsonResponse('Category deleted', 200);
        } else {
            return new JsonResponse('Not Found', 404);
        }
    }
}