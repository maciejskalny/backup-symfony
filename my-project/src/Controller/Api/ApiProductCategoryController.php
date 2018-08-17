<?php
/**
 * This file is a controller which supports Category Rest api.
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */
namespace App\Controller\Api;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Api\ApiProductCategoryType;

class ApiProductCategoryController extends Controller
{
    /**
     * @Route("/api/category/{id}")
     * @Method("GET")
     * @param $id
     * @return Response
     */
    public function showCategory($id)
    {
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id' => $id]);
        if($category)
        {
            return new Response(json_encode($category->getCategoryInfo()));
        }
        else{
            return new Response('Not Found', 404);
        }
    }
    /**
     * @Route("api/categories")
     * @Method("GET")
     * @return Response
     */
    public function showAllCategories()
    {
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAll();
        $data = array('categories' => array());
        foreach ($categories as $category){
            $data['categories'][] = $category->serializeCategory();
        }
        return new Response(json_encode($data), 200);
    }
    /**
     * @Route("api/category")
     * @Method("POST")
     * @param Request $request
     * @return Response
     */
    public function newCategory(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $category = new ProductCategory();
        $form = $this->createForm(ApiProductCategoryType::class, $category);
        $form->submit($request->query->all());
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        return new Response('New category added.', 200);
    }
    /**
     * @Route("api/category/{id}/edit")
     * @Method("PUT")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function editCategory(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_encode($request->getContent(), true);
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id'=>$id]);
        if($category) {
            $form = $this->createForm(ApiProductCategoryType::class, $category);
            $form->submit($request->query->all());
            $em->flush();
            return new Response('Category updated', 200);
        }
        else{
            return new Response('Bad Request', 400);
        }
    }
    /**
     * @Route("api/category/{id}/delete")
     * @Method("DELETE")
     * @param $id
     * @return Response
     */
    public function deleteCategory($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $this->getDoctrine()->getRepository(ProductCategory::class)->findOneBy(['id' => $id]);
        if($category){
            $em->remove($category);
            $em->flush();
            return new Response('Category deleted', 200);
        }
        else{
            return new Response('Not Found', 404);
        }
    }
}