<?php

namespace TylerSommer\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function getSidebarData()
    {
        return $this->createQueryBuilder('t')
            ->where('t.active = true')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
