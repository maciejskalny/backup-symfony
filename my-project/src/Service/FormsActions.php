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
     * @param Form $form
     * @return array
     */
    public function showErrors(Form $form)
    {
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