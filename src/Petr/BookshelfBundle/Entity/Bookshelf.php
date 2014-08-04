<?php

namespace Petr\BookshelfBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Bookshelf
 * @ORM\Table(name="bookshelf", indexes={@ORM\Index(name="index_name", columns={"name"})})
 * @ORM\Entity(repositoryClass="Petr\BookshelfBundle\Repository\BookshelfRepository")
 */
class Bookshelf {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /** @ORM\ManyToMany(targetEntity="Book") */
    private $books;


    public function __construct() {
        $this->books = new ArrayCollection();
    }

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
     * @return Bookshelf
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
     * Get books
     * @return ArrayCollection
     */
    public function getBooks() {
        return $this->books;
    }
}
