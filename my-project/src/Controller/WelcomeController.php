<?php

/**
 * This file is a controller which supports main page
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

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WelcomeController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class WelcomeController extends Controller
{
    /**
     * Shows home page
     *
     * @Route("/welcome", name="welcome")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render(
            'welcome/index.html.twig',
            [
                'controller_name' => 'WelcomeController',
                ]
        );
    }
}
