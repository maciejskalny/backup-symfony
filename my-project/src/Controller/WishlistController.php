<?php

/**
 * This file is a controller which is responsible for wishlist
 * @category Controller
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
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
 * @package App\Controller
 */
class WishlistController extends Controller
{

    /**
     * @Route("/wishlist", name="wishlist")
     * @param Session $session
     * @return Response
     */
    public function index(Session $session)
    {
        if($session->has('wishlist')) {
            $em = $this->getDoctrine()->getManager();

            return $this->render('wishlist/index.html.twig', [
                'wishlist' => $session->get('wishlist'),
                'products' => $em->getRepository(Product::class)->findBy(['id' => $session->get('wishlist')])
            ]);
        } else {
            return $this->render('wishlist/index.html.twig');
        }
    }

    /**
     * @Route("/wishlist/add/{id}", name="wishlist_add", methods="GET|POST")
     * @param Session $session
     * @param $id
     * @return Response
     */
    public function new(Session $session, $id)
    {
        if(!$session->isStarted()) {
            $session->start();
        }

        if($session->has('wishlist')) {
        $wishlist = $session->get('wishlist');
        } else {
            $wishlist = array();
        }

        array_push($wishlist, $id);
        $session->set('wishlist', $wishlist);

        $em = $this->getDoctrine()->getManager();

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $session->get('wishlist'),
            'products' => $em->getRepository(Product::class)->findBy(['id' => $session->get('wishlist')])
        ]);
    }

    /**
     * @Route("/wishlist/delete/{id}", name="wishlist_delete", methods="GET|POST")
     * @param Session $session
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function delete(Session $session, $id)
    {
        if(!$session->isStarted()) {
            return $this->render('wishlist/index.html.twig');
        }

        if($session->has('wishlist')) {
            $wishlist = $session->get('wishlist');
        }

        unset($wishlist[array_search($id, $wishlist)]);

        $session->set('wishlist', $wishlist);

        return $this->redirectToRoute('wishlist');
    }

    /**
     * @Route("/wishlist/delete", name="wishlist_delete_all", methods="GET|POST")
     * @param Session $session
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteAll(Session $session)
    {
        if(!$session->isStarted()) {
            return $this->render('wishlist/index.html.twig');
        }

        if($session->has('wishlist')) {
            $wishlist = $session->get('wishlist');
        }

        $session->remove('wishlist');

        return $this->redirectToRoute('wishlist');
    }
}