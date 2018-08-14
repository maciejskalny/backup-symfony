<?php
/**
 * This file supports images actions
 * @category Service
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Service;

use App\Entity\Image;
use App\Entity\ProductCategory;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Collection;

class ImagesActions
{
    /**
     * @var
     */
    private $imagesDirectory;

    /**
     * ImagesCollection constructor.
     * @param $imagesDirectory
     */
    public function __construct($imagesDirectory)
    {
        $this->imagesDirectory = $imagesDirectory;
    }

    /**
     * @param $images
     * @return ArrayCollection
     */
    public function createImagesCollection($images)
    {
        $parameterValue = $this->imagesDirectory;
        $filesCollection = new ArrayCollection();

        foreach ($images as $image)
        {
            $file = new Image();
            $ext = $image->guessExtension();
            $file->setName($image.'.'.$ext);

            $image->move(
                $parameterValue,
                $file->getName()
            );

            $file->setName(substr(strrchr($image, "/"), 1).'.'.$ext);
            $filesCollection->add($file);
        }

        return $filesCollection;
    }

    /**
     * @param $image
     * @return Image
     */
    public function createImage($image)
    {
        $parameterValue = $this->imagesDirectory;

        $file = new Image();

        $ext = $image->guessExtension();
        $file->setName($image.'.'.$ext);
        $test = $file->getName();

        $image->move(
            $parameterValue,
            $test
        );

        $file->setName(substr(strrchr($image, "/"), 1).'.'.$ext);

        return $file;
    }
}