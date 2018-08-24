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
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Component\Filesystem\Filesystem;
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
     * @param string $name
     * @param string|null $csvFile
     * @param string|null $choices
     */
    public function export(string $name, string $csvFile=null, string $choices=null)
    {
        $fileSystem = new Filesystem();

        if(!$fileSystem->exists($this->csvDirectory)) {
            $fileSystem->mkdir($this->csvDirectory);
        }

        if(!isset($csvFile)) {
            $fileName = $this->csvDirectory . '/export_' . $name . '_' . date('d-m-Y-H:i:s') . '.csv';
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
     * @param FormInterface $form
     * @param String $name
     */
    public function import(FormInterface $form, String $name)
    {
        $line = 0;

        foreach ($this->prepareData($form) as $row) {

            $line++;
            $entity = null;

            if(isset($row['id'])) {
                $entity = $this->getRepository($name)->findOneBy(['id' => $row['id']]);
            }

            try {
                $this->prepareEntity($row, $name, $line, $entity);
            } catch (\Exception $e) {
                $this->addFlashMessage($line, $e->getMessage());
            }
        }
    }

    /**
     * @param String $name
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
     * @param array $row
     * @param String $name
     * @param $entity
     * @param Int $line
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * @param array $row
     * @param Int $line
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepareProductEntity(Array $row, Int $line, $entity=null)
    {
        if ($category = $this->em->getRepository(ProductCategory::class)->findOneBy(['id' => $row['category']])) {
            if($entity == null ) {
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
     * @param array $row
     * @param Int $line
     * @param $entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepareCategoryEntity(Array $row, Int $line, $entity=null)
    {
        if($entity == null) {
            $entity = new ProductCategory();
        }
        $entity->setDataFromArray($row);
        $this->em->persist($entity);
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

    /**
     * @param string $name
     * @param string|null $choices
     * @return array
     */
    public function findEntity(string $name, string $choices=null)
    {
        if($choices == null)
        {
            $repository = $this->getRepository($name)->findAll();
        } else {
            $choices = explode(',',$choices);
            $repository = $this->getRepository($name)->findBy(['id' => $choices]);
        }

        foreach ($repository as $item) {
            $data[] = $item->getExportInfo();
        }

        return $data;
    }
}