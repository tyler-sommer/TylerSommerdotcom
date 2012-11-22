<?php

namespace TylerSommer\Bundle\BlogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TylerSommer\Bundle\BlogBundle\Entity\Tag;
use TylerSommer\Bundle\BlogBundle\Form\TagType;

/**
 * Tag controller.
 *
 * @Route("/manage/tag")
 */
class TagController extends Controller
{
    /**
     * Lists all Tag entities.
     *
     * @Route("s", name="manage_tags")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TylerSommerBlogBundle:Tag')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Displays a form to create a new Tag entity.
     *
     * @Route("/new", name="manage_tag_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tag();
        $form   = $this->createForm(new TagType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Tag entity.
     *
     * @Route("/create", name="manage_tag_create")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Admin/Tag:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Tag();
        $form = $this->createForm(new TagType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The tag has been created successfully.');

            return $this->redirect($this->generateUrl('manage_tags'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/{id}/edit", name="manage_tag_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $form = $this->createForm(new TagType(), $entity);

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing Tag entity.
     *
     * @Route("/{id}/update", name="manage_tag_update")
     * @Method("POST")
     * @Template("TylerSommerBlogBundle:Admin/Tag:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TylerSommerBlogBundle:Tag')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tag entity.');
        }

        $form = $this->createForm(new TagType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->getSession()->getFlashBag()->set('success', 'The tag has been updated successfully.');

            return $this->redirect($this->generateUrl('manage_tags'));
        }

        return array(
            'entity'      => $entity,
            'form'   => $form->createView(),
        );
    }
}
