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
class CsvActions {

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
    public function prepareData(FormInterface $form){
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
        if($name == 'category') {
            $this->importCategory($form);
        } else if($name == 'product') {
            $this->importProduct($form);
        }
    }

    /**
     * @param FormInterface $form
     */
    public function importProduct(FormInterface $form)
    {
        $line = 0;
        foreach ($this->prepareData($form) as $row)
        {
            $line++;
            $checkId = $this->em->getRepository(Product::class)->findOneBy(['id' => $row['id']]);
            if(!isset($checkId)) {
                if(isset($row['category'])) {
                    if ($category = $this->em->getRepository(ProductCategory::class)->findOneBy(['id' => $row['category']])) {
                        $product = new Product();
                        try {
                            $product->setDataFromArray($row, $category);
                            $this->em->persist($product);
                            $this->em->flush();
                        } catch (\Exception $e) {
                            $this->session->getFlashBag()->add(
                                'notice',
                                'Something went wrong in imported file, at line ' . $line . ': ' . $e->getMessage()
                            );
                        }
                    } else {
                        $this->session->getFlashBag()->add(
                            'notice',
                            'Something went wrong in imported file, at line ' . $line . ': there is no category with that id.'
                        );
                    }
                } else {
                    $this->session->getFlashBag()->add(
                        'notice',
                        'Something went wrong in imported file, at line '.$line.': category field does not exist.'
                    );
                }
            } else {
                $this->session->getFlashBag()->add(
                    'notice',
                    'Something went wrong in imported file, at line '.$line.': product with that id already exists.'
                );
            }
        }
    }

    /**
     * @param FormInterface $form
     */
    public function importCategory(FormInterface $form)
    {
        $line = 0;
        foreach ($this->prepareData($form) as $row)
        {
            $line++;
            $checkId = $this->em->getRepository(ProductCategory::class)->findOneBy(['id' => $row['id']]);
            if(!isset($checkId)) {
                $category = new ProductCategory();
                try{
                    $category->setDataFromArray($row);
                    $this->em->persist($category);
                    $this->em->flush();
                } catch (\Exception $e){
                    $this->session->getFlashBag()->add(
                        'notice',
                        'Something went wrong in imported file, at line ' . $line . ': ' . $e->getMessage()
                    );
                }
            } else {
                $this->session->getFlashBag()->add(
                    'notice',
                    'Something went wrong in imported file, at line '.$line.': category with that id already exists.'
                );
            }
        }
    }

}