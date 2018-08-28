<?php

/**
 * This file supports export and imports actions
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

use App\Entity\Product;
use App\Entity\ProductCategory;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\ORM\EntityManager;

/**
 * Class CsvActions
 *
 * @category Class
 * @package  App\Service
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class CsvActions
{
    /**
     * Directory for .csv files.
     *
     * @var string
     */
    private $csvDirectory;

    /**
     * Supports connecting with database.
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Supports flash messages.
     *
     * @var Session
     */
    private $session;

    /**
     * CsvActions constructor.
     *
     * @param String        $csvDirectory
     * @param EntityManager $em
     * @param Session       $session
     */
    public function __construct(
        String $csvDirectory,
        EntityManager $em,
        Session $session
    ) {
        $this->csvDirectory = $csvDirectory;
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * Preparing data from imported file
     *
     * @param File|string $file
     *
     * @return array
     */
    public function prepareData($file)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $data = $serializer->decode(file_get_contents($file), 'csv');
        return $data;
    }

    /**
     * Supports export
     *
     * @param string      $name
     * @param string|null $csvFile
     * @param string|null $choices
     *
     * @return void
     */
    public function export(string $name, string $csvFile=null, string $choices=null)
    {
        $fileSystem = new Filesystem();

        if (!$fileSystem->exists($this->csvDirectory)) {
            $fileSystem->mkdir($this->csvDirectory);
        }

        if (!isset($csvFile)) {
            $fileName = $this
                    ->csvDirectory.'/export_'.$name.'_'.date('d-m-Y-H:i:s').'.csv';
        } else {
            $fileName = $this->csvDirectory . '/' . $csvFile . '.csv';
        }

        $file = fopen($fileName, "w");

        foreach ($this->findEntity($name, $choices) as $line) {
            fputcsv(
                $file,
                $line,
                ','
            );
        }
        fclose($file);
    }

    /**
     * Supports import
     *
     * @param String      $name
     * @param File|string $file
     *
     * @return void
     */
    public function import(String $name, $file)
    {
        $line = 0;

        foreach ($this->prepareData($file) as $row) {

            $line++;
            $entity = null;

            if (isset($row['id'])) {
                $entity = $this
                    ->getRepository($name)
                    ->findOneBy(['id' => $row['id']]);
            }

            try {
                $this->prepareEntity($row, $name, $line, $entity);
            } catch (\Exception $e) {
                $this->addFlashMessage($line, $e->getMessage());
            }
        }
    }

    /**
     * Preparing repository
     *
     * @param String $name
     *
     * @return null|object
     */
    public function getRepository(String $name)
    {
        if ($name == 'category') {
            return $this->em->getRepository(ProductCategory::class);
        } else {
            return $this->em->getRepository(Product::class);
        }
    }

    /**
     * Preparing entity
     *
     * @param array  $row
     * @param String $name
     * @param Int    $line
     * @param $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return void
     */
    public function prepareEntity(Array $row, String $name, Int $line, $entity=null)
    {
        if ($name == 'category') {
            $this->prepareCategoryEntity($row, $line, $entity);
        } else {
            $this->prepareProductEntity($row, $line, $entity);
        }
    }

    /**
     * Preparing product entity
     *
     * @param array  $row
     * @param Int    $line
     * @param $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return void
     */
    public function prepareProductEntity(Array $row, Int $line, $entity=null)
    {
        if ($category = $this->em->getRepository(ProductCategory::class)->findOneBy(['id' => $row['category']])) {
            if ($entity == null ) {
                $entity = new Product();
            }
            $entity->setDataFromArray($row, $category);
            $this->em->persist($entity);
            $this->em->flush();
        } else {
            $this->addFlashMessage($line, 'Category with that id does not exist.');
        }
    }

    /**
     * Preparing category entity
     *
     * @param array  $row
     * @param Int    $line
     * @param $entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @return void
     */
    public function prepareCategoryEntity(Array $row, Int $line, $entity=null)
    {
        if ($entity == null) {
            $entity = new ProductCategory();
        }
        $entity->setDataFromArray($row);
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * Adding flash message
     *
     * @param Int    $line
     * @param String $error
     *
     * @return string
     */
    public function addFlashMessage(Int $line, String $error)
    {
        return $this->session->getFlashBag()->add(
            'notice',
            'Something went wrong in imported file, at line '.$line.': '.$error
        );
    }

    /**
     * Finds entity
     *
     * @param string      $name
     * @param string|null $choices
     *
     * @return array
     */
    public function findEntity(string $name, string $choices=null)
    {
        if ($choices == null) {
            $repository = $this->getRepository($name)->findAll();
        } else {
            $choices = explode(',', $choices);
            $repository = $this->getRepository($name)->findBy(['id' => $choices]);
        }

        foreach ($repository as $item) {
            $data[] = $item->getExportInfo();
        }

        return $data;
    }
}