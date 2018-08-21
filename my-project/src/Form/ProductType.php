<?php

/**
 * This file supports product form
 * @category Form
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Form;

use App\Entity\ProductImage;
use App\Entity\ProductCategory;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Webmozart\Assert\Assert;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;

/**
 * Class ProductType
 * @package App\Form
 */
class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => ProductCategory::class,
                'choice_label' => 'name'])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'data_class' => null,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '400k',
                        'maxSizeMessage' => 'Too large file.',
                        'mimeTypes' => [
                            '.png' => 'image/png',
                            '.jpg' => 'image/jpg',
                            '.jpeg' => 'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'Your file must be a .png, .jpg or .jpeg!'
                    ])
                ]
            ])
            ->add('imageFiles', CollectionType::class, [
                'entry_type' => FileType::class,
                'entry_options' => [
                    'label' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '400k',
                            'maxSizeMessage' => 'Too large file.',
                            'mimeTypes' => [
                                '.png' => 'image/png',
                                '.jpg' => 'image/jpg',
                                '.jpeg' => 'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Your file must be a .png, .jpg or .jpeg!'
                        ])
                    ]
                ],
                'allow_add' => true,
                'mapped' =>false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}