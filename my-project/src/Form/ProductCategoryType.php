<?php

/**
 * This file supports product category form
 *
 * PHP version 7.1.16
 *
 * @category  Form
 * @package   Virtua_Internship
 * @author    Maciej Skalny <contact@wearevirtua.com>
 * @copyright 2018 Copyright (c) Virtua (http://wwww.wearevirtua.com)
 * @license   GPL http://opensource.org/licenses/gpl-license.php
 * @link      https://github.com/maciejskalny/backup-symfony
 */

namespace App\Form;

use App\Entity\Image;
use App\Entity\ProductCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Webmozart\Assert\Assert;

/**
 * Class ProductCategoryType
 *
 * @category Class
 * @package  App\Form
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class ProductCategoryType extends AbstractType
{
    /**
     * Builds form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add(
                'imageFile', FileType::class, [
                    'required' => false,
                    'data_class' => null,
                    'mapped' => false,
                    'constraints' => [
                        new File(
                            [
                            'maxSize' => '400k',
                            'maxSizeMessage' => 'Too large file.',
                            'mimeTypes' => [
                                '.png' => 'image/png',
                                '.jpg' => 'image/jpg',
                                '.jpeg' => 'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Your file must be a .png, .jpg or .jpeg!'
                            ]
                        )
                    ]
                ]
            )
            ->add(
                'imageFiles', CollectionType::class, [
                    'entry_type' => FileType::class,
                    'entry_options' => [
                        'label' => false,
                        'constraints' => [
                            new File(
                                [
                                'maxSize' => '400k',
                                'maxSizeMessage' => 'Too large file.',
                                'mimeTypes' => [
                                    '.png' => 'image/png',
                                    '.jpg' => 'image/jpg',
                                    '.jpeg' => 'image/jpeg'
                                ],
                                'mimeTypesMessage' => 'Your file must be a .png, .jpg or .jpeg!'
                                ]
                            )
                        ]
                    ],
                    'allow_add' => true,
                    'mapped' => false,
                    ]
            );
    }

    /**
     * Configuring options
     *
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'data_class' => ProductCategory::class,
                ]
        );
    }
}