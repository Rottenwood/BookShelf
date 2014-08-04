<?php

namespace Petr\BookshelfBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Репозиторий запросов к таблице "Книги"
 */
class BookRepository extends EntityRepository {

    public function findBookAndAuthor($bookname, $author) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT b FROM PetrBookshelfBundle:Book b WHERE b.name = :bookname AND b.author = :author');
        $query->setParameter('bookname', $bookname);
        $query->setParameter('author', $author);
        $result = $query->getResult();

        return $result;
    }

}
