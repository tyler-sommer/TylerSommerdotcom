<?php

namespace TylerSommer\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Type\NullDateTime;
use Orkestra\Common\Type\DateTime;
use Orkestra\Common\Entity\EntityBase;

/**
 * A post
 *
 * @ORM\Entity
 * @ORM\Table(name="pages")
 * @ORM\HasLifecycleCallbacks
 */
class Page extends EntityBase
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_posted", type="datetime", nullable=true)
     */
    protected $datePublished;

    /**
     * @var \TylerSommer\Bundle\BlogBundle\Entity\Author
     *
     * @ORM\ManyToOne(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\Author", cascade={"persist"})
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * @param \TylerSommer\Bundle\BlogBundle\Entity\Author $author
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;
    }

    /**
     * @return \TylerSommer\Bundle\BlogBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->datePublished = new NullDateTime();
    }

    /**
     * @param $active
     */
    public function setActive($active)
    {
        if ($active) {
            $this->active = true;
            $this->datePublished = new DateTime();
        } else {
            $this->active = false;
            $this->datePublished = new NullDateTime();
        }
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param \DateTime $datePublished
     */
    public function setDatePublished(\DateTime $datePublished)
    {
        $this->datePublished = $datePublished;
    }

    /**
     * @return \DateTime
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        parent::prePersist();

        $this->datePublished = $this->active ? new DateTime() : new NullDateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        parent::preUpdate();

        $this->datePublished = $this->active ? new DateTime() : new NullDateTime();
    }
}
