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
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks
 */
class Post extends EntityBase
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\Comment", mappedBy="post")
     */
    protected $comments;

    /**
     * @var \TylerSommer\Bundle\BlogBundle\Entity\Author
     *
     * @ORM\ManyToOne(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\Author", cascade={"persist"})
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

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
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
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
