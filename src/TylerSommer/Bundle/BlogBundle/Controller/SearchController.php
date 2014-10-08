<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Controller;

use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use TylerSommer\Bundle\BlogBundle\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new SearchType());
        $form->bind($request);

        $terms = null;
        $entities = array();
        if ($form->isValid()) {
            $terms = $form->get('search')->getData();
            $entities = $this->getSearchResults($terms);
        }

        return array(
            'terms' => array('terms' => $terms),
            'form' => $form->createView(),
            'entities' => $entities
        );
    }

    /**
     * @Route("/search/category/{name}", name="search_category")
     * @Template("TylerSommerBlogBundle:Search:index.html.twig")
     */
    public function searchCategoryAction($name)
    {
        $form = $this->createForm(new SearchType());

        $entities = $this->getByCategory($name);

        return array(
            'terms' => array('category' => $name),
            'form' => $form->createView(),
            'entities' => $entities
        );
    }

    /**
     * @Route("/search/tag/{name}", name="search_tag")
     * @Template("TylerSommerBlogBundle:Search:index.html.twig")
     */
    public function searchTagAction($name)
    {
        $form = $this->createForm(new SearchType());

        $entities = $this->getByTag($name);

        return array(
            'terms' => array('tag' => $name),
            'form' => $form->createView(),
            'entities' => $entities
        );
    }

    protected function getByTag($tag)
    {
        $entities = $this->getRepository('TylerSommerBlogBundle:Post')->findByTag($tag);
        return array_merge($entities, $this->getRepository('TylerSommerBlogBundle:Page')->findByTag($tag));
    }

    protected function getByCategory($category)
    {
        $entities = $this->getRepository('TylerSommerBlogBundle:Post')->findByCategory($category);
        return array_merge($entities, $this->getRepository('TylerSommerBlogBundle:Page')->findByCategory($category));
    }

    protected function getSearchResults($searchTerms)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p')
            ->from('TylerSommerBlogBundle:Post', 'p')
            ->where('p.active = true')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.title', ':terms'),
                $qb->expr()->like('p.body', ':terms')
            ))
            ->setParameter('terms', '%' . $searchTerms . '%');

        $entities = $qb->getQuery()->getResult();

        $qb->resetDQLPart('from')
            ->from('TylerSommerBlogBundle:Page', 'p');

        $entities = array_merge($entities, $qb->getQuery()->getResult());

        return $entities;
    }
}
