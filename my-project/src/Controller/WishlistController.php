<?php

/**
 * This file is a controller which is responsible for wishlist
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

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WishlistController
 *
 * @category Class
 * @package  App\Controller
 * @author   Maciej Skalny <contact@wearevirtua.com>
 * @license  GPL http://opensource.org/licenses/gpl-license.php
 * @link     https://github.com/maciejskalny/backup-symfony
 */
class WishlistController extends Controller
{
    /**
     * Shows all products in wish list
     *
     * @param Session $session
     *
     * @Route("/wishlist", name="wishlist")
     *
     * @return Response
     */
    public function index(Session $session)
    {
        if ($session->has('wishlist')) {
            $em = $this->getDoctrine()->getManager();

            return $this->render(
                'wishlist/index.html.twig', [
                    'wishlist' => $session->get('wishlist'),
                    'products' => $em->getRepository(Product::class)->findBy(['id' => $session->get('wishlist')])
                ]
            );
        } else {
            return $this->render('wishlist/index.html.twig');
        }
    }

    /**
     * Adding new product to wish list
     *
     * @param Session $session
     * @param int     $id
     *
     * @Route("/wishlist/add/{id}", name="wishlist_add", methods="GET|POST")
     *
     * @return Response
     */
    public function new(Session $session, $id)
    {
        if (!$session->isStarted()) {
            $session->start();
        }

        if ($session->has('wishlist')) {
            $wishlist = $session->get('wishlist');
        } else {
            $wishlist = [];
        }

        if (sizeof($wishlist)<5) {
            array_push($wishlist, $id);
            $session->set('wishlist', $wishlist);
        } else {
            $session->getFlashBag()->add('error', 'You can add only 5 products to the wishlist.');
        }

        $em = $this->getDoctrine()->getManager();

        return $this->render(
            'wishlist/index.html.twig', [
                'wishlist' => $session->get('wishlist'),
                'products' => $em->getRepository(Product::class)->findBy(['id' => $session->get('wishlist')])
            ]
        );
    }

    /**
     * Removes product from wish list
     *
     * @param Session $session
     * @param int     $id
     *
     * @Route("/wishlist/delete/{id}", name="wishlist_delete", methods="GET|POST")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function delete(Session $session, $id)
    {
        if ($session->isStarted() && $session->has('wishlist')) {
            $wishlist = $session->get('wishlist');

            unset($wishlist[array_search($id, $wishlist)]);

            if (sizeof($wishlist) == null) {
                $session->remove('wishlist');
            } else {
                $session->set('wishlist', $wishlist);
            }
        }

        return $this->redirectToRoute('wishlist');
    }

    /**
     * Removes all products from wish list
     *
     * @param Session $session
     *
     * @Route("/wishlist/delete", name="wishlist_delete_all", methods="GET|POST")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAll(Session $session)
    {
        if ($session->isStarted() && $session->has('wishlist')) {
            $session->remove('wishlist');
        }

        return $this->redirectToRoute('wishlist');
    }
}
