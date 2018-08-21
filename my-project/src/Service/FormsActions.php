<?php
/**
 * This file supports forms actions
 * @category Service
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Service;

use Symfony\Component\Form\Form;

/**
 * Class FormsActions
 * @package App\Service
 */
class FormsActions{

    /**
     * @var Form
     */
    private $form;

    /**
     * FormsActions constructor.
     * @param $form
     */
    public function __construct($form)
    {
        $this->form = $form;
    }

    /**
     * @return array
     */
    public function showErrors()
    {
        $form = $this->form;

        $errors = [];

        foreach ($form as $child)
        {
            if(!$child->isValid()){
                foreach ($child->getErrors() as $error)
                {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}