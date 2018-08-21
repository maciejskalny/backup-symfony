<?php
/**
 * This file is a controller which supports Product Rest api.
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Form\Api\ApiProductType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\FormsActions;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiProductController
 * @package App\Controller\Api
 */
class ApiProductController extends Controller
{
    /**
     * @Route("/api/product/{id}")
     * @Method("GET")
     * @param $id
     * @return JsonResponse
     */
    public function showProduct($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['id' => $id]);
        if($product) {
            return new JsonResponse(json_encode($product->getProductInfo()));
        } else {
            return new JsonResponse('Not Found.', 404);
        }
    }

    /**
     * @Route("api/products")
     * @Method("GET")
     * @return JsonResponse
     */
    public function showAllProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $data = ['products' => []];
        foreach ($products as $product) {
            $data['products'][] = $product->serializeProduct();
        }
        return new JsonResponse(json_encode($data), 200);
    }

    /**
     * @Route("api/product")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function newProduct(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        try {
            $product = new Product();
            $form = $this->createForm(ApiProductType::class, $product);
            $form->handleRequest($request);
            $form->submit($request->query->all());
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return new JsonResponse('New product added.', 201);
        } catch (\Exception $exception) {
            if($form->isValid()){
                return new JsonResponse('Bad request.', 400);
            } else {
                $formsActionsService = new FormsActions($form);
                return new JsonResponse('Bad request: '.json_encode($formsActionsService->showErrors()), 400);
            }
        }
    }

    /**
     * @Route("/api/product/{id}/edit")
     * @Method("PUT")
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function editProduct(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['id' => $id]);
        if($product) {
            try {
                $form = $this->createForm(ApiProductType::class, $product);
                $form->submit($request->query->all());
                $em->flush();
                return new JsonResponse('Product updated.', 200);
            } catch (\Exception $exception) {
                if($form->isValid()){
                    return new JsonResponse('Bad request.', 400);
                } else {
                    $formsActionsService = new FormsActions($form);
                    return new JsonResponse('Bad request: '.json_encode($formsActionsService->showErrors()), 400);
                }
            }
        } else {
            return new JsonResponse('Not found.', 404);
        }
    }

    /**
     * @Route("/api/product/{id}/delete")
     * @Method("DELETE")
     * @param $id
     * @return JsonResponse
     */
    public function deleteProduct($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository(Product::class)->findOneBy(['id' => $id]);
        if($product) {
            $em->remove($product);
            $em->flush();
            return new JsonResponse('Product deleted.', 200);
        } else {
            return new JsonResponse('Not Found.', 404);
        }
    }
}