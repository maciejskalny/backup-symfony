<?php

/**
 * This file supports product entity
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
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductImage", mappedBy="product", cascade={"persist"}, orphanRemoval=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *     maxSize = "400k",
     *     maxSizeMessage = "Too large file",
     *     mimeTypes = {"image/png", "image/jpg", "image/jpeg"},
     *     mimeTypesMessage = "Your file must be a .pdf, .jpg or .jpeg!",
     * )
     */
    private $mainImage;

    public function __construct()
    {
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

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|ProductImage[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(ProductImage $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(ProductImage $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return array
     */
    public function serializeProduct(){
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
        );
    }
    /**
     * @return array
     */
    public function getProductInfo()
    {
        $data = [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'category' => $this->getCategory(),
            'created_at' => $this->getAddDate(),
            'last_modified' => $this->getLastModifiedDate(),
        ];
        return $data;
    }
}