<?php

/**
 * This file is a controller which supports export to csv file and import to database.
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Controller;

use App\Form\ImportType;
use App\Service\CsvActions;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @param CsvActions $csvActionsService
     * @param $name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("{name}/", name="import", methods="GET|POST")
     */
    public function import(Request $request, CsvActions $csvActionsService, $name)
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $csvActionsService->import($form, $name);

            if($name == 'category') {
                return $this->redirectToRoute('product_category_index');
            } else {
                return $this->redirectToRoute('product_index');
            }
        }

        return $this->render('csv/import.html.twig', [
            'form' => $form->createView(),
            'name' => $name
        ]);
    }
}