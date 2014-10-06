<?php

namespace TylerSommer\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A comment
 *
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    protected $body;

    /**
     * @var \TylerSommer\Bundle\BlogBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\AbstractPost", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * @var \TylerSommer\Bundle\BlogBundle\Entity\Author
     *
     * @ORM\ManyToOne(targetEntity="TylerSommer\Bundle\BlogBundle\Entity\Author", inversedBy="comments", cascade={"persist"})
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
     * @param \TylerSommer\Bundle\BlogBundle\Entity\AbstractPost $post
     */
    public function setPost(AbstractPost $post)
    {
        $this->post = $post;
    }

    /**
     * @return \TylerSommer\Bundle\BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
