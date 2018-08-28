<?php
/**
 * This file is a controller which supports Product Rest api.
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

use App\Entity\Product;
use App\Form\Api\ApiProductType;
use App\Service\FormsActions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ApiProductController
 *
 * @category Class
 * @package  App\Controller\Api
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class ApiProductController extends Controller
{
    /**
     * Shows one product
     *
     * @param integer $id
     *
     * @Route("/api/product/{id}")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function showProduct($id)
    {
        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findOneBy(['id' => $id]);

        if ($product) {
            return new JsonResponse(json_encode($product->getProductInfo()));
        } else {
            return new JsonResponse('Not Found.', 404);
        }
    }

    /**
     * Shows all products
     *
     * @Route("api/products")
     * @Method("GET")
     *
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
     * Creates new product
     *
     * @param Request      $request
     * @param FormsActions $formsActionsService
     *
     * @Route("api/product")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function newProduct(Request $request, FormsActions $formsActionsService)
    {
        $product = new Product();
        $form = $this->createForm(ApiProductType::class, $product);
        $form->handleRequest($request);
        $form->submit($request->query->all());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return new JsonResponse('New product added.', 201);
        } else {
            return new JsonResponse(
                'Bad request: ' . json_encode(
                    $formsActionsService->showErrors($form)
                ),
                400
            );
        }
    }

    /**
     * Updates product
     *
     * @param Request      $request
     * @param FormsActions $formsActionsService
     * @param integer      $id
     *
     * @Route("/api/product/{id}/edit")
     * @Method("PUT")
     *
     * @return JsonResponse
     */
    public function editProduct(
        Request $request,
        FormsActions $formsActionsService,
        $id
    ) {
        $em = $this->getDoctrine()->getManager();
        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findOneBy(['id' => $id]);

        if ($product) {
            $form = $this->createForm(ApiProductType::class, $product);
            $form->submit($request->query->all());
            if ($form->isValid()) {
                $em->flush();
                return new JsonResponse('Product updated.', 200);
            } else {
                return new JsonResponse(
                    'Bad request: '.json_encode(
                        $formsActionsService->showErrors($form)
                    ),
                    400
                );
            }
        } else {
            return new JsonResponse('Not found.', 404);
        }
    }

    /**
     * Removes product
     *
     * @param integer $id
     *
     * @Route("/api/product/{id}/delete")
     * @Method("DELETE")
     *
     * @return JsonResponse
     */
    public function deleteProduct($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findOneBy(['id' => $id]);

        if ($product) {
            $em->remove($product);
            $em->flush();
            return new JsonResponse('Product deleted.', 200);
        } else {
            return new JsonResponse('Not Found.', 404);
        }
    }
}