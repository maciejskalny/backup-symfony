<?php
/**
 * This file supports forms actions
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

use Symfony\Component\Form\Form;

/**
 * Class FormsActions
 *
 * @category Class
 * @package  App\Service
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class FormsActions
{
    /**
     * Shows errors
     *
     * @param Form $form
     *
     * @return array
     */
    public function showErrors(Form $form)
    {
        $errors = [];

        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }
        return $errors;
    }
}
