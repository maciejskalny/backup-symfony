<?php

/**
 * This file is a controller which is responsible for navigation
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

use App\Entity\ProductCategory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\ProductCategoryRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NavController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class NavController extends Controller
{

    /**
     * Supports rendering categories name for navigation
     *
     * @param ProductCategoryRepository $categories
     *
     * @return Response
     */
    public function items(ProductCategoryRepository $categories)
    {
        return $this->render('nav/nav_items.html.twig', ['categories' => $categories->findAll()]);
    }
}