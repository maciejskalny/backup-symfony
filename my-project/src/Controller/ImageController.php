<?php

/**
 * This file is a controller which is responsible for image actions
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
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
 * @package App\Controller
 * @Route("/image")
 */
class ImageController extends Controller
{
    /**
     * @Route("/{id}", name="image_delete", methods="DELETE")
     * @param Request $request
     * @param Image $image
     * @param ImagesActions $imagesActions
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