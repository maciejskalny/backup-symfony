<?php

/**
 * This file supports product category entity
 * @category Entity
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
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
 * @ORM\Entity(repositoryClass="App\Repository\ProductCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $add_date;

    /**
     * @ORM\Column(type="date")
     */
    private $last_modified_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="main_image_id", referencedColumnName="id")
     * @Assert\File(
     *     maxSize = "400k",
     *     maxSizeMessage = "Too large file",
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Your file must be a .pdf, .jpg or .jpeg!",
     *    )
     */
    private $mainImage;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinTable(name="images_categories",
     *     joinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true)})
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProductCategory
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return ProductCategory
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    /**
     * @ORM\PrePersist
     */
    public function setAddDate()
    {
        $this->add_date = new \DateTime();
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastModifiedDate(): ?\DateTimeInterface
    {
        return $this->last_modified_date;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setLastModifiedDate()
    {
        $this->last_modified_date = new \DateTime();
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
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
     * @param Product $product
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
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param ArrayCollection $images
     * @return $this
     */
    public function addImages(ArrayCollection $images)
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @param Image $image
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
     * @return Image|null
     */
    public function getMainImage(): ?Image
    {
        return $this->mainImage;
    }

    /**
     * @param Image|null $mainImage
     * @return ProductCategory
     */
    public function setMainImage(?Image $mainImage): self
    {
        $this->mainImage = $mainImage;
        $this->images = $mainImage;
        return $this;
    }
}