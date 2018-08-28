<?php

/**
 * This file is a controller which is responsible for image actions
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

use App\Entity\Image;
use App\Entity\ProductCategory;
use App\Repository\ImageRepository;
use App\Service\ImagesActions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Class ImageController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 *
 * @Route("/image")
 */
class ImageController extends Controller
{
    /**
     * Removes image
     *
     * @param Request       $request
     * @param Image         $image
     * @param ImagesActions $imagesActions
     *
     * @Route("/{id}", name="image_delete", methods="DELETE")
     *
     * @return Response
     */
    public function delete(Request $request, Image $image, ImagesActions $imagesActions): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();

            $imagesActions->removeImage($image);

            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('product_category_index');
    }
}