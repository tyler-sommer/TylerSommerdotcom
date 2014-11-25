<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use TylerSommer\Bundle\BlogBundle\Entity\AbstractPost;

class PostRepository extends EntityRepository
{
    /**
     * Finds all Posts by the given category
     *
     * @param string $category
     *
     * @return array|AbstractPost[]
     */
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('p.active = true')
            ->andWhere('c.name = :name')
            ->setParameter('name', $category)
            ->orderBy('p.datePublished', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Finds all Posts by the given tag
     *
     * @param string $tag
     *
     * @return array|AbstractPost[]
     */
    public function findByTag($tag)
    {
        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('p.active = true')
            ->andWhere('t.name = :name')
            ->setParameter('name', $tag)
            ->orderBy('p.datePublished', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * This special method is used by the UniqueEntity validator
     *
     * Queries the superclass to ensure a slug is unique across all subclasses
     *
     * @param $criteria
     *
     * @return array|null
     */
    public function findByUnique($criteria)
    {
        if (!isset($criteria['slug'])) {
            return null;
        }

        return $this->_em->createQueryBuilder()
            ->select('p')
            ->from('TylerSommerBlogBundle:AbstractPost', 'p')
            ->where('p.slug = :slug')
            ->setParameter('slug', $criteria['slug'])
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns true if the slug is unique
     *
     * @param string $slug
     *
     * @return bool
     */
    public function isSlugUnique($slug)
    {
        return count($this->findByUnique(array('slug' => $slug))) === 0;
    }

    /**
     * Find all posts for the home page
     * 
     * @return array|AbstractPost[]
     */
    public function findForHome()
    {
        return $this->_em->createQueryBuilder()
            ->select('p, t, c, co')
            ->from($this->_entityName, 'p')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('p.comments', 'co')
            ->andWhere('p.active = true')
            ->orderBy('p.datePublished', 'DESC')
            ->getQuery()
            ->setMaxResults(5)
            ->getResult();
    }

    /**
     * Find one post by its slug
     * 
     * @param string $slug
     *
     * @return AbstractPost
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneBySlug($slug)
    {
        return $this->_em->createQueryBuilder()
            ->select('p, t, c, co')
            ->from('TylerSommerBlogBundle:AbstractPost', 'p')
            ->leftJoin('p.tags', 't')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('p.comments', 'co')
            ->where('p.slug = :slug')
            ->andWhere('p.active = true')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
