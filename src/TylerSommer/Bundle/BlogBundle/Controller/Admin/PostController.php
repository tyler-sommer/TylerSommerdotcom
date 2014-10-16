<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TylerSommer\Bundle\BlogBundle\Entity\Post;
use TylerSommer\Bundle\BlogBundle\Form\PostType;

/**
 * Post controller.
 *
 * @Route("/manage/post")
 */
class PostController extends Controller
{
    /**
     * Lists all Post entities.
     *
     * @Route("s/", name="manage_posts")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TylerSommerBlogBundle:Post')
            ->createQueryBuilder('p')
            ->orderBy('p.active', 'ASC')
            ->addOrderBy('p.datePublished', 'DESC')
            ->getQuery()
            ->getResult();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id}/show", name="manage_post_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Finds and displays a Post entity.
     *
     * @Route("/{id}/preview", name="manage_post_preview")
     * @Template("TylerSommerBlogBundle:Home:index.html.twig")
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        return array(
            'entities'      => array($entity),
        );
    }

    /**
     * Displays a form to create a new Post entity.
     *
     * @Route("/new", name="manage_post_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Post();
        $form   = $this->createForm(new PostType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Post entity.
     *
     * @Route("/create", name="manage_post_create")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Admin/Post:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Post();
        $form = $this->createForm(new PostType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The post has been created successfully.');

            return $this->redirect($this->generateUrl('manage_post_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Post entity.
     *
     * @Route("/{id}/edit", name="manage_post_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $form = $this->createForm(new PostType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing Post entity.
     *
     * @Route("/{id}/update", name="manage_post_update")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Admin/Post:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        $form = $this->createForm(new PostType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The post has been updated successfully.');

            return $this->redirect($this->generateUrl('manage_post_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Returns a JSON response describing if the given slug is unique
     *
     * @Route("/is-slug-unique", name="manage_post_is_slug_unique")
     * @Method("POST")
     */
    public function isSlugUniqueAction(Request $request)
    {
        return new JsonResponse(array(
            'unique' => $this->getRepository('TylerSommerBlogBundle:Post')->isSlugUnique($request->get('slug')))
        );
    }
}
