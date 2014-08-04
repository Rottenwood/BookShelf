<?php

namespace Petr\BookshelfBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий запросов к таблице "Книжные полки (библиотеки)"
 */
class BookshelfRepository extends EntityRepository {

    public function findShelfByName($shelfName) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT s FROM PetrBookshelfBundle:Bookshelf s WHERE s.name LIKE :shelfname');
        $query->setParameter('shelfname', '%' . $shelfName . '%');
        $result = $query->getResult();

        return $result;
    }

    public function findBookInShelf($bookName, $shelf) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT s FROM PetrBookshelfBundle:Bookshelf s LEFT JOIN s.books b WHERE s.name LIKE
            :shelf AND b.id LIKE :book');
        $query->setParameter('shelf', '%' . $shelf . '%');
        $query->setParameter('book', '%' . $bookName . '%');
        $result = $query->getResult();

        return $result;
    }
}
