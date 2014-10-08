<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TylerSommer\Bundle\BlogBundle\Entity\Post;
use TylerSommer\Bundle\BlogBundle\Form\PostType;

/**
 * Comment controller.
 *
 * @Route("/post/{postid}/comment")
 */
abstract class AbstractBlogController extends Controller
{
    const CURRENT_AUTHOR_SESSION_KEY      = '__current_author_id';

    private $currentAuthor;

    protected function getCurrentAuthor()
    {
        if ($this->currentAuthor) {
            return $this->currentAuthor;
        }

        if ($this->getSession()->has(static::CURRENT_AUTHOR_SESSION_KEY)) {
            $this->currentAuthor = $this->getDoctrine()->getManager()->find('TylerSommerBlogBundle:Author', $this->getSession()->get(self::CURRENT_AUTHOR_SESSION_KEY));
        } else {
            $this->currentAuthor = $this->getDoctrine()
                ->getManager()
                ->getRepository('TylerSommerBlogBundle:Author')
                ->findOneBy(array('user' => $this->getUser()));
        }

        if (!$this->currentAuthor) {
            throw new \RuntimeException('This action requires that the current user be assigned to an author');
        }

        return $this->currentAuthor;
    }
}
