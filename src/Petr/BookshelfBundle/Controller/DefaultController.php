<?php

namespace Petr\BookshelfBundle\Controller;

use Petr\BookshelfBundle\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository("PetrBookshelfBundle:Book")->findAll();

        return $this->render('PetrBookshelfBundle:Default:index.html.twig', array('books' => $books));
    }
}
