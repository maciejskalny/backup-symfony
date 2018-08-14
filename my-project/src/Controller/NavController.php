<?php

/**
* This file is a controller which is responsible for navigation
* @category Controller
* @Package Virtua_Internship
* @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
* @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Controller;

use App\Entity\ProductCategory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Repository\ProductCategoryRepository;

class NavController extends Controller
{

    public function items(ProductCategoryRepository $categories)
    {
        return $this->render('nav/nav_items.html.twig', ['categories' => $categories->findAll()]);
    }
}