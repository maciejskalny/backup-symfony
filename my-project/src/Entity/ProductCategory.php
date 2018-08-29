<?php

/**
 * This file supports product category entity
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
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinColumns;

/**
 * Class ProductCategory
 *
 * @category Class
 * @package  App\Entity
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductCategory
{
    /**
     * Id of the category
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name of the category
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotNull()
     */
    private $name;

    /**
     * Description of the category
     *
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotNull()
     */
    private $description;

    /**
     * When category has been created
     *
     * @ORM\Column(type="date")
     */
    private $add_date;

    /**
     * When category has been modified last
     *
     * @ORM\Column(type="date")
     */
    private $last_modified_date;

    /**
     * Category products
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Product",
     *     mappedBy="category",
     *     cascade={"persist", "remove"})
     */
    private $products;

    /**
     * Category main image
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
     * Category gallery
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Image",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true)
     * @ORM\JoinTable(name="images_categories",
     *     joinColumns={@ORM\JoinColumn(
     *     name="category_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE"
     * )},
     *     inverseJoinColumns={@ORM\JoinColumn(
     *     name="image_id",
     *     referencedColumnName="id",
     *     unique=true,
     *     onDelete="CASCADE"
     * )
     * })
     */
    private $images;

    /**
     * ProductCategory constructor.
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * Gets category id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets category name
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets category name
     *
     * @param string $name
     *
     * @return ProductCategory
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets category description
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets category description
     *
     * @param null|string $description
     *
     * @return ProductCategory
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets when category has been created
     *
     * @return \DateTimeInterface|null
     */
    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    /**
     * Sets when category has been created
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
     * Gets when category has been modified last
     *
     * @return \DateTimeInterface|null
     */
    public function getLastModifiedDate(): ?\DateTimeInterface
    {
        return $this->last_modified_date;
    }

    /**
     * Sets when category has been modified last
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
     * Gets all category products
     *
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * Adding new product to category
     *
     * @param Product $product
     *
     * @return ProductCategory
     */
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

    /**
     * Removes product from category
     *
     * @param Product $product
     *
     * @return ProductCategory
     */
    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * Gets category gallery
     *
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * Adding images to category gallery
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
     * Removes image from gallery
     *
     * @param Image $image
     *
     * @return ProductCategory
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }

    /**
     * Gets category main image
     *
     * @return Image|null
     */
    public function getMainImage(): ?Image
    {
        return $this->mainImage;
    }

    /**
     * Sets category main image
     *
     * @param Image|null $mainImage
     *
     * @return ProductCategory
     */
    public function setMainImage(?Image $mainImage): self
    {
        $this->mainImage = $mainImage;
        $this->images->add($mainImage);

        return $this;
    }

    /**
     * Preparing some category data for rest api
     *
     * @return array
     */
    public function serializeCategory()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    /**
     * Gets category info for rest api
     *
     * @return array
     */
    public function getCategoryInfo()
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'created_at' => $this->getAddDate(),
            'last_modified' => $this->getLastModifiedDate(),
            'products' => [],
        ];
        foreach ($this->getProducts() as $product) {
            $data['products'][] = $product->serializeProduct();
        }
        return $data;
    }

    /**
     * Sets some new info to category
     *
     * @param array|null $row
     *
     * @throws \Exception
     *
     * @return void
     */
    public function setDataFromArray($row)
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
    }

    /**
     * Gets some category info for export
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
            'created_at' => $createdAt,
            'last_modified' => $lastModified
        ];
    }
}
