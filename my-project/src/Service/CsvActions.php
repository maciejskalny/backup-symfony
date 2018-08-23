<?php

/**
 * This file supports export and imports actions
 * @category Service
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductCategory;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

/**
 * Class CsvActions
 * @package App\Service
 */
class CsvActions
{

    /**
     * Directory for .csv files.
     * @var string
     */
    private $csvDirectory;

    /**
     * Supports connecting with database.
     * @var EntityManager
     */
    private $em;

    /**
     * Supports flash messages.
     * @var Session
     */
    private $session;

    /**
     * CsvActions constructor.
     * @param String $csvDirectory
     * @param EntityManager $em
     * @param Session $session
     */
    public function __construct(String $csvDirectory, EntityManager $em, Session $session)
    {
        $this->csvDirectory = $csvDirectory;
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public function prepareData(FormInterface $form)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $data = $serializer->decode(file_get_contents($form->get('importFile')->getData()), 'csv');
        return $data;
    }

    /**
     * @param FormInterface $form
     * @param String $name
     */
    public function import(FormInterface $form, String $name)
    {
        $line = 0;

        foreach ($this->prepareData($form) as $row) {

            $line++;

            $repository = $this->getEntityRepository($row, $name);

            if(is_null($repository)) {
                try {
                    $this->prepareEntity($row, $name, $line);
                } catch (\Exception $e) {
                    $this->addFlashMessage($line, $e->getMessage());
                }
            } else {
                $this->addFlashMessage($line, 'Entity with that id already exists.');
            }
        }
    }

    /**
     * @param array $row
     * @param String $name
     * @return null|object
     */
    public function getEntityRepository(Array $row, String $name)
    {
        if ($name == 'category') {
            return $this->em->getRepository(ProductCategory::class)->findOneBy(['id' => $row['id']]);
        } else {
            return $this->em->getRepository(Product::class)->findOneBy(['id' => $row['id']]);
        }
    }

    /**
     * @param array $row
     * @param String $name
     * @param Int $line
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepareEntity(Array $row, String $name, Int $line)
    {
        if ($name == 'category') {
            $this->prepareCategoryEntity($row, $line);
        } else {
            $this->prepareProductEntity($row, $line);
        }
    }

    /**
     * @param array $row
     * @param Int $line
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepareProductEntity(Array $row, Int $line)
    {
        if ($category = $this->em->getRepository(ProductCategory::class)->findOneBy(['id' => $row['category']])) {
            $product = new Product();
            $product->setDataFromArray($row, $category);
            $this->em->persist($product);
            $this->em->flush();
        } else {
            $this->addFlashMessage($line, 'Category with that id does not exist.');
        }
    }

    /**
     * @param array $row
     * @param Int $line
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepareCategoryEntity(Array $row, Int $line)
    {
        $category = new ProductCategory();
        $category->setDataFromArray($row);
        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * @param Int $line
     * @param String $error
     * @return string
     */
    public function addFlashMessage(Int $line, String $error)
    {
        return $this->session->getFlashBag()->add(
            'notice',
            'Something went wrong in imported file, at line '.$line.': '.$error
        );
    }
}