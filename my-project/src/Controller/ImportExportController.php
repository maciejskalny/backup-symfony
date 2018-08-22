<?php

/**
 * This file is a controller which supports export to csv file and import to database.
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/categories/import", name="categories_import")
     */
    public function importCategories(Request $request, CsvActions $csvActionsService)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ImportCategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $csvActionsService->import($form);

            //return $this->redirectToRoute('product_category_index');
        }

        return $this->render('product_category/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    public function import(Request $request, CsvActions $csvActionsService)
//    {
//        $form = $this->createForm(ImportCategoryType::class);
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid())
//        {
//            $csvActionsService->import($form);
//
//            return $this->redirectToRoute('product_category_index');
//        }
//
//        return $this->render('product_category/import.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
}