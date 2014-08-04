<?php
/**
 * Author: Petr
 */

namespace Petr\BookshelfBundle\Service;

use Doctrine\ORM\EntityManager;
use Petr\BookshelfBundle\Entity\Book;
use Petr\BookshelfBundle\Entity\Bookshelf;

class BookService {

    protected $em;
    protected $bookRepository;
    protected $shelfRepository;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->bookRepository = $this->em->getRepository("PetrBookshelfBundle:Book");
        $this->shelfRepository = $this->em->getRepository("PetrBookshelfBundle:Bookshelf");
    }

    /**
     * Создать книгу
     * @param $bookname
     * @param $author
     * @return bool
     */
    public function createBook($bookname, $author) {
//        $bookname = "Test";
//        $author = "Test";
        $bookExist = $this->bookRepository->findBookAndAuthor($bookname, $author);

        $result = array();

        if (!$bookExist) {
            $newBook = new Book();
            $newBook->setName($bookname);
            $newBook->setAuthor($author);

            $this->em->persist($newBook);
            $this->em->flush();
            $result["bookid"] = $newBook->getId();
            $result["message"] = "Книга добавлена! Название: $bookname, автор: $author";
        } else {
            $result["message"] = "Книга уже существует";
            $result["error"] = "0:1";
        }

        return $result;
    }

    /**
     * Создать книгу в библиотеке
     * @param $bookname
     * @param $author
     * @param $shelfId
     * @return bool
     */
    public function createBookInShelf($bookname, $author, $shelfId) {
        $bookExist = $this->bookRepository->findBookAndAuthor($bookname, $author);

        $result = array();

        if ($bookExist) {
            $newBook = $bookExist[0];
        } else {
            $newBook = new Book();
            $newBook->setName($bookname);
            $newBook->setAuthor($author);

            $this->em->persist($newBook);
        }

        // список книг в библиотеке
        $shelf = $this->shelfRepository->find($shelfId);
        $booksInShelf = $shelf->getBooks();
        $bookInShelfArray = $booksInShelf->toArray();

        // поиск книги в библиотеке
        if (!in_array($newBook, $bookInShelfArray)) {
            $booksInShelf->add($newBook);
            $this->em->persist($shelf);
            $this->em->flush();
            $result["bookid"] = $newBook->getId();
            $result["message"] = "Книга добавлена! Название: $bookname, автор: $author";
        } else {
            $result["message"] = "Книга уже существует";
            $result["error"] = "0:1";
        }

        return $result;
    }

    /**
     * Редактировать книгу
     * @param int    $bookId
     * @param string $newname
     * @param string $newauthor
     * @internal param $bookid
     * @return bool
     */
    public function editBook($bookId, $newname, $newauthor) {
        /** @var Book $book */
        $book = $this->bookRepository->find($bookId);

        $result = array();

        if ($book) {
            $book->setName($newname);
            $book->setAuthor($newauthor);

            $this->em->persist($book);
            $this->em->flush();

            $result["message"] = 'Книга "' . $newname . '" отредактирована!';
        } else {

            $result["message"] = 'Книга "' . $newname . '" для редактирования не найдена';
        }

        return $result;
    }

    /**
     * Удалить книгу
     * @param $bookId
     * @return bool
     */
    public function deleteBook($bookId) {
        /** @var Book $book */
        $book = $this->bookRepository->find($bookId);

        $result = array();

        if ($book) {
            $this->em->remove($book);
            $this->em->flush();

            $result["message"] = "Книга $bookId удалена!";
        } else {

            $result["message"] = "Книга для удаления $bookId не найдена";
        }

        return $result;
    }

    /**
     * Создать библиотеку
     * @param $shelfName
     * @return array
     */
    public function createShelf($shelfName) {
        $shelfExist = $this->shelfRepository->findShelfByName($shelfName);

        $result = array();

        if ($shelfExist) {
            $result["message"] = "Библиотека уже существует";
        } else {
            $newShelf = new Bookshelf();
            $newShelf->setName($shelfName);

            $this->em->persist($newShelf);
            $this->em->flush();

            $result["shelfid"] = $newShelf->getId();
            $result["message"] = 'Библиотека создана! Название: "' . $shelfName . '"';
        }

        return $result;
    }

    /**
     * Редактировать библиотеку
     * @param $shelfId
     * @param $newName
     * @return array
     */
    public function editBookShelf($shelfId, $newName) {
        /** @var Bookshelf $bookShelf */
        $bookShelf = $this->shelfRepository->find($shelfId);

        $result = array();

        if ($bookShelf) {
            $bookShelf->setName($newName);

            $this->em->persist($bookShelf);
            $this->em->flush();

            $result["message"] = 'Библиотека "' . $newName . '" отредактирована!';
        } else {
            $result["message"] = 'Библиотека "' . $newName . '" для редактирования не найдена';
        }

        return $result;
    }

    /**
     * Удаление библиотеки
     * @param $shelfId
     * @return array
     */
    public function deleteBookShelf($shelfId) {
        /** @var Bookshelf $bookShelf */
        $bookShelf = $this->shelfRepository->find($shelfId);

        $result = array();

        $bookShelfName = $bookShelf->getName();

        if ($bookShelf) {
            $this->em->remove($bookShelf);
            $this->em->flush();

            $result["message"] = 'Библиотека "' . $bookShelfName . '" удалена!';
        } else {

            $result["message"] = 'Библиотека "' . $bookShelfName . '" не найдена';
        }

        return $result;
    }
}
