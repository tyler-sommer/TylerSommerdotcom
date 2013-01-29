<?php

namespace TylerSommer\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * An author
 *
 * @ORM\Entity
 * @ORM\Table(name="authors")
 */
class Author extends AbstractEntity
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\Post", mappedBy="author")
     */
    protected $posts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\Comment", mappedBy="author")
     */
    protected $comments;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\User
     *
     * @ORM\OneToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts    = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->user ? $this->user->__toString() : '';
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
     * @param Post $post
     */
    public function addPost(Post $post)
    {
        $this->posts->add($post);
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
