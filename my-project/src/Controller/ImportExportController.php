<?php

/**
 * This file is a controller which supports import to csv file and export do database.
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ImportCategoryType;
use App\Service\CsvActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class ImportExportController
 * @package App\Controller
 * @Route("/csv")
 */
class ImportExportController extends Controller
{
    /**
     * @Route("/categories/import", name="categories_import")
     */
    public function importCategories(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ImportCategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
            $data = $serializer->decode(file_get_contents($form->get('importFile')->getData()), 'csv');
            foreach ($data as $row)
            {
               
                $category = new ProductCategory();
                $category->setName($row['name']);
                $category->setDescription($row['description']);
                $category->setAddDate($row['created_at']);
                $category->setLastModifiedDate($row['last_modified']);

                $em->persist($category);
                $em->flush();
            }
        }

        return $this->render('product_category/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     * @param CsvActions $csvActionsService
     * @Route("/categories/export", name="categories_export")
     */
    public function exportCategories(CsvActions $csvActionsService)
    {
        $categories = $this->getDoctrine()->getRepository(ProductCategory::class)->findAll();
        $data = [];

        foreach ($categories as $category){
            $data[] = $category->getSomeCategoryInfo();
        }

        $csvActionsService->createCsvFile($data);

        return $this->redirectToRoute('product_category_index');
    }
}