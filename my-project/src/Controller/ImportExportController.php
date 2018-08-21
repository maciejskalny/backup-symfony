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
use App\Service\CsvActions;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ImportExportController
 * @package App\Controller
 * @Route("/csv")
 */
class ImportExportController extends Controller
{
    /**
     * @return Response
     * @param CsvActions $csvActionsService
     * @Route("/categories", name="categories_export")
     */
    public function exportCategoriesToCsv(CsvActions $csvActionsService)
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