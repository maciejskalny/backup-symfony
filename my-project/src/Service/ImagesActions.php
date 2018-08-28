<?php
/**
 * This file supports images actions
 *
 * PHP version 7.1.16
 *
 * @category  Service
 * @package   Virtua_Internship
 * @author    Maciej Skalny <contact@wearevirtua.com>
 * @copyright 2018 Copyright (c) Virtua (http://wwww.wearevirtua.com)
 * @license   GPL http://opensource.org/licenses/gpl-license.php
 * @link      https://github.com/maciejskalny/backup-symfony
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
 *
 * @category Class
 * @package  App\Service
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class ImagesActions
{
    /**
     * Service parameter, a directory with uploaded images.
     *
     * @var string
     */
    private $imagesDirectory;

    /**
     * ImagesCollection constructor.
     *
     * @param string $imagesDirectory
     */
    public function __construct($imagesDirectory)
    {
        $this->imagesDirectory = $imagesDirectory;
    }

    /**
     * Creates images collection
     *
     * @param $images
     *
     * @return ArrayCollection
     */
    public function createImagesCollection($images)
    {
        $imageManager = new ImagesActions($this->imagesDirectory);
        $filesCollection = new ArrayCollection();

        foreach ($images as $image) {
            $filesCollection->add($imageManager->createImage($image));
        }

        return $filesCollection;
    }

    /**
     * Create one image
     *
     * @param $image
     *
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
     * Removes image
     *
     * @param Image|null $image
     *
     * @return void
     */
    public function removeImage(?Image $image)
    {
        $parameterValue = $this->imagesDirectory;
        $file = new Filesystem();

        $file->remove($parameterValue.'/'.$image->getName());
    }
}