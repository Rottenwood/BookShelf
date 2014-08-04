<?php

namespace Petr\BookshelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 * @ORM\Table(name="books", indexes={@ORM\Index(name="index_name_author", columns={"book_name", "author"})})
 * @ORM\Entity(repositoryClass="Petr\BookshelfBundle\Repository\BookRepository")
 */
class Book {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="book_name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Book
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set author
     * @param string $author
     * @return Book
     */
    public function setAuthor($author) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }
}
