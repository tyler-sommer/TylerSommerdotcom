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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use TylerSommer\Bundle\BlogBundle\Entity\AbstractPost;
use TylerSommer\Bundle\BlogBundle\Entity\Comment;
use TylerSommer\Bundle\BlogBundle\Entity\Post;
use TylerSommer\Bundle\BlogBundle\Form\CommentType;
use TylerSommer\Bundle\BlogBundle\Form\PostType;

/**
 * Comment controller.
 *
 * @Route("/post/{postid}/comment")
 */
class CommentController extends AbstractBlogController
{
    /**
     * Lists all of a post's comments.
     *
     * @Route("s", name="post_comments")
     * @Template()
     */
    public function indexAction($postid)
    {
        $post = $this->getPost($postid);

        $entities = $post->getComments()->toArray();
        usort($entities, function(Comment $a, Comment $b) {
            return $a->getDateCreated() < $b->getDateCreated();
        });

        return array(
            'post'     => $post,
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Comment entity.
     *
     * @Route("/new", name="post_comment_new")
     * @Secure("ROLE_COMMENT_WRITE")
     * @Template()
     */
    public function newAction($postid)
    {
        $post = $this->getPost($postid);

        $entity = new Comment();
        $form   = $this->createForm(new CommentType(), $entity);

        return array(
            'entity' => $entity,
            'post'   => $post,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Comment entity.
     *
     * @Route("/create", name="post_comment_create")
     * @Secure("ROLE_COMMENT_WRITE")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Comment:new.html.twig")
     */
    public function createAction(Request $request, $postid)
    {
        $post = $this->getPost($postid);

        $entity = new Comment();
        $entity->setPost($post);
        $entity->setAuthor($this->getCurrentAuthor());
        $form   = $this->createForm(new CommentType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The comment has been created successfully.');

            return $this->redirect($this->generateUrl('page_or_post', array('slug' => $post->getSlug())));
        }

        return array(
            'entity' => $entity,
            'post'   => $post,
            'form'   => $form->createView(),
        );
    }

    /**
     * @param int $postid
     *
     * @return AbstractPost
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function getPost($postid)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('TylerSommerBlogBundle:AbstractPost')
            ->createQueryBuilder('p')
            ->addSelect('c')
            ->leftJoin('p.comments', 'c')
            ->where('p.id = :post_id')
            ->setParameters(array(
                'post_id' => $postid
            ))
            ->getQuery()
            ->getOneOrNullResult();

        if (!$post) {
            throw $this->createNotFoundException('Unable to locate Post');
        }

        return $post;
    }
}
