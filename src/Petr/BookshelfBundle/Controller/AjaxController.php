<?php

namespace Petr\BookshelfBundle\Controller;

use Petr\BookshelfBundle\Entity\Book;
use Petr\BookshelfBundle\Entity\Bookshelf;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller {

    /**
     * Ajax API: список книг в библиотеке
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return JsonResponse
     */
    public function getBookListAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $shelfId = $request->request->get('shelf');
        $shelf = $em->getRepository("PetrBookshelfBundle:Bookshelf")->find($shelfId);

        $result = array();

        if (!$shelf) {
            $result[]["error"] = "0:2";
        } else {
            /** @var Bookshelf $shelf */
            $books = $shelf->getBooks();

            if (!$books) {
                $result[]["error"] = "0:3";
            } else {
                foreach ($books as $book) {
                    /** @var Book $book */
                    $bookId = $book->getId();
                    $result[$bookId]["bookname"] = $book->getName();
                    $result[$bookId]["bookauthor"] = $book->getAuthor();
                }
            }
        }

        return new JsonResponse($result);
    }

    /**
     * Ajax API: список всех книг
     * @return JsonResponse
     */
    public function getBookListAllAction() {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository("PetrBookshelfBundle:Book")->findAll();

        $result = array();

        if (!$books) {
            $result[]["error"] = "0:3";
        } else {
            foreach ($books as $book) {
                /** @var Book $book */
                $bookId = $book->getId();
                $result[$bookId]["bookname"] = $book->getName();
                $result[$bookId]["bookauthor"] = $book->getAuthor();
            }
        }

        return new JsonResponse($result);
    }

    /**
     * Ajax API: создание книги
     * @param Request $request
     * @return JsonResponse
     */
    public function bookCreateAction(Request $request) {
        $bookname = $request->request->get('bookname');
        $author = $request->request->get('author');
        $result = $this->get('book')->createBook($bookname, $author);

        return new JsonResponse($result);
    }

    /**
     * Ajax API: создание книги в библиотеке
     * @param Request $request
     * @return JsonResponse
     */
    public function bookCreateInShelfAction(Request $request) {
        $bookname = $request->request->get('bookname');
        $author = $request->request->get('author');
        $shelf = $request->request->get('shelf');
        $result = $this->get('book')->createBookInShelf($bookname, $author, $shelf);

        return new JsonResponse($result);
    }

    /**
     * Ajax API: редактирование книги
     * @param Request $request
     * @return JsonResponse
     */
    public function bookEditAction(Request $request) {
        $bookId = $request->request->get('bookid');
        $bookname = $request->request->get('bookname');
        $author = $request->request->get('author');
        $result = $this->get('book')->editBook($bookId, $bookname, $author);
        return new JsonResponse($result);
    }

    /**
     * Ajax API: удаление книги
     * @param Request $request
     * @return JsonResponse
     */
    public function bookDeleteAction(Request $request) {
        $bookId = $request->request->get('bookid');
        $result = $this->get('book')->deleteBook($bookId);
        return new JsonResponse($result);
    }

    /**
     * Ajax API: список всех библиотек
     * @return JsonResponse
     */
    public function getShelfsAction() {
        $em = $this->getDoctrine()->getManager();
        $shelfs = $em->getRepository("PetrBookshelfBundle:Bookshelf")->findAll();

        $result = array();

        foreach ($shelfs as $shelf) {
            /** @var Bookshelf $shelf */
            $shelfId = $shelf->getId();
            $result[$shelfId]["shelfname"] = $shelf->getName();
            $result[$shelfId]["shelfbooks"] = $shelf->getBooks();
        }

        return new JsonResponse($result);
    }

    /**
     * Ajax API: создание библиотеки
     * @param Request $request
     * @return JsonResponse
     */
    public function shelfCreateAction(Request $request) {
        $result = array();

        $shelfName = $request->request->get('shelfname');
        if ($shelfName) {
            $result = $this->get('book')->createShelf($shelfName);
        } else {
            $result["message"] = "Библиотека не создана: не указано имя.";
        }
        return new JsonResponse($result);
    }

    /**
     * Ajax API: редактирование библиотеки
     * @param Request $request
     * @return JsonResponse
     */
    public function bookShelfEditAction(Request $request) {
        $shelfId = $request->request->get('shelf');
        $newName = $request->request->get('bookShelfName');
        $result = $this->get('book')->editBookShelf($shelfId, $newName);

        return new JsonResponse($result);
    }

    /**
     * Ajax API: удаление библиотеки
     * @param Request $request
     * @return JsonResponse
     */
    public function bookShelfDeleteAction(Request $request) {
        $shelfId = $request->request->get('bookShelf');
        $result = $this->get('book')->deleteBookShelf($shelfId);
        return new JsonResponse($result);
    }

}
