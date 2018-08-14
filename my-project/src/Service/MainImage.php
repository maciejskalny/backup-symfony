<?php
/**
 * Created by PhpStorm.
 * User: virtua
 * Date: 03.08.2018
 * Time: 10:09
 */

namespace App\Service;

use App\Entity\Image;
use App\Entity\ProductCategory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class MainImage
{
    private $imagesDirectory;

    public function __construct($imagesDirectory)
    {
        $this->imagesDirectory = $imagesDirectory;
    }

    public function addingMainImage($ProductCategory, $mainImage)
    {
        $parameterValue = $this->imagesDirectory;

        $ext = $mainImage->guessExtension();
        $mainImageName = $mainImage.'.'.$ext;
        $ProductCategory->setMainImage($mainImage . '.' .$ext);

        $mainImage->move(
            $parameterValue,
            $mainImageName
        );

        $ProductCategory->setMainImage(substr(strrchr($mainImage, "/"), 1) . '.' . $ext);

        $file = new Image();
        $file->setName(substr(strrchr($mainImage, "/"), 1) . '.' . $ext);
        $ProductCategory->addImage($file);
    }
}


