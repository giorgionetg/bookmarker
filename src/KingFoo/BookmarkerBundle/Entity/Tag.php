<?php

namespace KingFoo\BookmarkerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="KingFoo\BookmarkerBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity="Bookmark", mappedBy="tags")
     */
    private $bookmarks;

    function __construct($label)
    {
        $this->label = $label;
        $this->bookmarks = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getBookmarks()
    {
        return $this->bookmarks;
    }

    function __toString()
    {
        return $this->getLabel();
    }
}
