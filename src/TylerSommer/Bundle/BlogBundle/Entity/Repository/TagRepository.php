<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    /**
     * Gets sidebar data
     *
     * @return array
     */
    public function getSidebarData()
    {
        return $this->createQueryBuilder('t')
            ->where('t.active = true')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
