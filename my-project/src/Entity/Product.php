<?php

/**
 * This file supports product entity.
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
     * @Assert\NotNull()
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotNull()
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", cascade={"persist"}, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     *
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="main_image_id", referencedColumnName="id")
     */
    private $mainImage;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinTable(name="images_products",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id", unique=true, onDelete="CASCADE")})
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
     * @return integer
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
     * @return Product
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
     * @return Product
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
     * @return ProductCategory|null
     */
    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    /**
     * @param ProductCategory|null $category
     * @return Product
     */
    public function setCategory(?ProductCategory $category): self
    {
        $this->category = $category;

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
     * @return Image|null
     */
    public function getMainImage(): ?Image
    {
        return $this->mainImage;
    }

    /**
     * @param Image|null $mainImage
     * @return Product
     */
    public function setMainImage(?Image $mainImage): self
    {

        $this->mainImage = $mainImage;
        $this->images->add($mainImage);

        return $this;
    }

    /**
     * @return array
     */
    public function serializeProduct(){
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    /**
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
     * @param array|null $row
     * @param ProductCategory $category
     * @throws \Exception
     */
    public function setDataFromArray(?Array $row, ProductCategory $category)
    {
        if(!empty($row['name'])) {
            $this->setName($row['name']);
        } else {
            throw new \Exception('Name field cant be null.');
        }

        if(!empty($row['description'])) {
            $this->setDescription($row['description']);
        } else {
            throw new \Exception('Description field cant be null.');
        }

        $this->setCategory($category);
    }
}