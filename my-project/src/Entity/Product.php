<?php

/**
 * This file supports product entity.
 *
 * PHP version 7.1.16
 *
 * @category  Entity
 * @package   Virtua_Internship
 * @author    Maciej Skalny <contact@wearevirtua.com>
 * @copyright 2018 Copyright (c) Virtua (http://wwww.wearevirtua.com)
 * @license   GPL http://opensource.org/licenses/gpl-license.php
 * @link      https://github.com/maciejskalny/backup-symfony
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 *
 * @category Class
 * @package  App\Entity
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * Id of the product
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name of the product
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     */
    private $name;

    /**
     * Description of the product
     *
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotNull()
     */
    private $description;

    /**
     * When product has been created
     *
     * @ORM\Column(type="date")
     */
    private $add_date;

    /**
     * When product has been modified
     *
     * @ORM\Column(type="date")
     */
    private $last_modified_date;

    /**
     * Product category
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", cascade={"persist"}, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $category;

    /**
     * Product main image
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Image",
     *     cascade={"persist"},
     *     orphanRemoval=true
     *     )
     * @ORM\JoinColumn(name="main_image_id", referencedColumnName="id")
     */
    private $mainImage;

    /**
     * Product gallery images
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Image",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     * @ORM\JoinTable(name="images_products",
     *     joinColumns={@ORM\JoinColumn(
     *     name="product_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )},
     *     inverseJoinColumns={@ORM\JoinColumn(
     *     name="image_id",
     *     referencedColumnName="id",
     *     unique=true,
     *     onDelete="CASCADE"
     *  )
     * })
     */
    private $images;

    /**
     * Product constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Get product id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets product name
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets product name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets product description
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets product description
     *
     * @param null|string $description
     *
     * @return Product
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets when product has been created
     *
     * @return \DateTimeInterface|null
     */
    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    /**
     * Sets when product has been created
     *
     * @ORM\PrePersist
     *
     * @return void
     */
    public function setAddDate()
    {
        $this->add_date = new \DateTime();
    }

    /**
     * Gets when product has been modified last
     *
     * @return \DateTimeInterface|null
     */
    public function getLastModifiedDate(): ?\DateTimeInterface
    {
        return $this->last_modified_date;
    }

    /**
     * Sets when product has been modified last
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function setLastModifiedDate()
    {
        $this->last_modified_date = new \DateTime();
    }

    /**
     * Gets product category
     *
     * @return ProductCategory|null
     */
    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    /**
     * Sets product category
     *
     * @param ProductCategory|null $category
     *
     * @return Product
     */
    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Gets gallery
     *
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * Adding images to product gallery
     *
     * @param ArrayCollection $images
     *
     * @return $this
     */
    public function addImages(ArrayCollection $images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * Removing image
     *
     * @param Image $image
     *
     * @return Product
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }

    /**
     * Gets product main image
     *
     * @return Image|null
     */
    public function getMainImage(): ?Image
    {
        return $this->mainImage;
    }

    /**
     * Sets product main image
     *
     * @param Image|null $mainImage
     *
     * @return Product
     */
    public function setMainImage(?Image $mainImage): self
    {

        $this->mainImage = $mainImage;
        $this->images->add($mainImage);

        return $this;
    }

    /**
     * Preparing some product data for rest api
     *
     * @return array
     */
    public function serializeProduct()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    /**
     * Preparing product fields for rest api
     *
     * @return array
     */
    public function getProductInfo()
    {
        $data = [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'category' => 'id: '.$this->getCategory()->getId().' name: '.$this->getCategory()->getName(),
            'created_at' => $this->getAddDate(),
            'last_modified' => $this->getLastModifiedDate(),
        ];
        return $data;
    }

    /**
     * Sets some new info to product
     *
     * @param array|null      $row
     * @param ProductCategory $category
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setDataFromArray(?Array $row, ProductCategory $category)
    {
        if (!empty($row['name'])) {
            $this->setName($row['name']);
        } else {
            throw new \Exception('Name field cant be null.');
        }

        if (!empty($row['description'])) {
            $this->setDescription($row['description']);
        } else {
            throw new \Exception('Description field cant be null.');
        }

        $this->setCategory($category);
    }

    /**
     * Gets some product info for export
     *
     * @return array
     */
    public function getExportInfo()
    {
        $createdAt = $this->getAddDate()->format('d/m/Y');
        $lastModified = $this->getLastModifiedDate()->format('d/m/Y');

        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'category' => $this->getCategory()->getId(),
            'created_at' => $createdAt,
            'last_modified' => $lastModified
        ];
    }
}
