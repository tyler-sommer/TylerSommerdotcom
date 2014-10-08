<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\TwigExtension;

use Symfony\Component\HttpFoundation\Request;
use TylerSommer\Bundle\BlogBundle\Entity\AbstractPost;
use TylerSommer\Bundle\BlogBundle\Entity\Page;
use TylerSommer\Bundle\BlogBundle\Entity\Post;

/**
 * Provides functionality related to pages and posts
 */
class PostExtension extends \Twig_Extension
{
    /**
     * @var Request|null
     */
    private $request;

    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function isCurrentSlug($slug)
    {
        if (!$this->request) {
            return false;
        }

        return $slug === $this->request->get('slug');
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('is_current_slug', array($this, 'isCurrentSlug'))
        );
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('post', function (AbstractPost $post) { return $post instanceof Post; }),
            new \Twig_SimpleTest('page', function (AbstractPost $post) { return $post instanceof Page; })
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'post';
    }
}
