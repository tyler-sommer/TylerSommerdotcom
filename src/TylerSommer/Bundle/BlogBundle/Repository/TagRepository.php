<?php

namespace TylerSommer\Bundle\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('t')
            ->where('t INSTANCE OF ' . $this->getClassName())
            ->getQuery()
            ->getResult();
    }
}
