<?php

/**
 * This file is a controller which is responsible for user registration
 *
 * PHP version 7.1.16
 *
 * @category  Controller
 * @package   Virtua_Internship
 * @author    Maciej Skalny <contact@wearevirtua.com>
 * @copyright 2018 Copyright (c) Virtua (http://wwww.wearevirtua.com)
 * @license   GPL http://opensource.org/licenses/gpl-license.php
 * @link      https://github.com/maciejskalny/backup-symfony
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegistrationController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class RegistrationController extends Controller
{
    /**
     * Registers new user
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @Route("/register", name="user_registration")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'You are registred.'
            );

            return $this->redirectToRoute('welcome');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form'=>$form->createView())
        );
    }
}
