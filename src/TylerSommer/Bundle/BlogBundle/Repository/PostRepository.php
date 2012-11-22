<?php

namespace TylerSommer\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findByCategory($category)
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->where('p.active = true')
            ->andWhere('c.name = :name')
            ->setParameter('name', $category)
            ->getQuery()
            ->getResult();
    }

    public function findByTag($tag)
    {
        return $this->createQueryBuilder('p')
            ->join('p.tags', 't')
            ->where('p.active = true')
            ->andWhere('t.name = :name')
            ->setParameter('name', $tag)
            ->getQuery()
            ->getResult();
    }
}
