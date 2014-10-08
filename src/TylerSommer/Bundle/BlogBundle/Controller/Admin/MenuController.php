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
use TylerSommer\Bundle\BlogBundle\Entity\Menu;
use TylerSommer\Bundle\BlogBundle\Form\MenuType;

/**
 * Menu controller.
 *
 * @Route("/manage/menu")
 */
class MenuController extends Controller
{
    /**
     * Lists all Menu entities.
     *
     * @Route("s/", name="manage_menus")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TylerSommerBlogBundle:Menu')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Menu entity.
     *
     * @Route("/{id}/show", name="manage_menu_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Finds and displays a Menu entity.
     *
     * @Route("/{id}/preview", name="manage_menu_preview")
     * @Template("TylerSommerBlogBundle:Home:index.html.twig")
     */
    public function previewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        return array(
            'entities'      => array($entity),
        );
    }

    /**
     * Displays a form to create a new Menu entity.
     *
     * @Route("/new", name="manage_menu_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Menu();
        $form   = $this->createForm('menu', $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Menu entity.
     *
     * @Route("/create", name="manage_menu_create")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Admin/Menu:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Menu();
        $form = $this->createForm('menu', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The menu has been created successfully.');

            return $this->redirect($this->generateUrl('manage_menu_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Menu entity.
     *
     * @Route("/{id}/edit", name="manage_menu_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $form = $this->createForm('menu', $entity);

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing Menu entity.
     *
     * @Route("/{id}/update", name="manage_menu_update")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Admin/Menu:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $form = $this->createForm('menu', $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The menu has been updated successfully.');

            return $this->redirect($this->generateUrl('manage_menu_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }
}
