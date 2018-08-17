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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * Class ImagesActions
 * @package App\Service
 */
class ImagesActions
{
    /**
     * Service parameter, a directory with uploaded images.
     * @var string
     */
    private $imagesDirectory;

    /**
     * ImagesCollection constructor.
     * Parameter is a path of a directory with uploaded images.
     * @param string $imagesDirectory
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
        $imageManager = new ImagesActions($this->imagesDirectory);
        $filesCollection = new ArrayCollection();

        foreach ($images as $image)
        {
            $filesCollection->add($imageManager->createImage($image));
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
        $fileName = $file->getName();

        $image->move(
            $parameterValue,
            $fileName
        );

        $file->setName(substr(strrchr($image, "/"), 1).'.'.$ext);

        return $file;
    }

    /**
     * @param Image|null $image
     */
    public function removeImage(?Image $image)
    {
        $parameterValue = $this->imagesDirectory;
        $file = new Filesystem();

        $file->remove($parameterValue.'/'.$image->getName());
    }
}