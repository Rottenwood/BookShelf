<?php

namespace Petr\BookshelfBundle\Controller;

use Petr\BookshelfBundle\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package Petr\BookshelfBundle\Controller
 */
class DefaultController extends Controller {

    /**
     * Главная страница
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        return $this->render('PetrBookshelfBundle:Default:index.html.twig');
    }
}
