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
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="category", cascade={"persist"}, orphanRemoval=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *   @Assert\File(
     *     maxSize = "400k",
     *     maxSizeMessage = "Too large file",
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Your file must be a .pdf, .jpg or .jpeg!",
     * )
     */

    private $main_image;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->image = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

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

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCategory($this);
        }

        return $this;
    }

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
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setCategory($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getCategory() === $this) {
                $image->setCategory(null);
            }
        }

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->main_image;
    }

    public function setMainImage(?string $main_image): self
    {
        $this->main_image = $main_image;

        return $this;
    }

    /**
     * @return array
     */
    public function serializeCategory(){
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
        );
    }

    /**
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
            'products' => array(),
        ];
        foreach ($this->getProducts() as $product)
        {
            $data['products'][] = $product->serializeProduct();
        }
        return $data;
    }
}