<?php

/**
 * This file is a controller which supports export and import.
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

namespace App\Controller;

use App\Form\ImportType;
use App\Service\CsvActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ImportExportController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 *
 * @Route("/csv")
 */
class ImportExportController extends Controller
{
    /**
     * Imports file
     *
     * @param Request    $request
     * @param CsvActions $csvActionsService
     * @param string     $name
     *
     * @Route("{name}/", name="import", methods="GET|POST")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function import(Request $request, CsvActions $csvActionsService, $name)
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvActionsService->import($name, $form->get('importFile')->getData());

            if ($name == 'category') {
                return $this->redirectToRoute('product_category_index');
            } else {
                return $this->redirectToRoute('product_index');
            }
        }

        return $this->render(
            'csv/import.html.twig',
            [
                'form' => $form->createView(),
                'name' => $name
            ]
        );
    }

    /**
     * Exports file
     *
     * @param CsvActions $csvActionsService
     * @param string     $name
     *
     * @Route("{name}/export", name="export")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function export(CsvActions $csvActionsService, $name)
    {
        $csvActionsService->export($name);

        if ($name == 'category') {
            return $this->redirectToRoute('product_category_index');
        } else {
            return $this->redirectToRoute('product_index');
        }
    }
}
