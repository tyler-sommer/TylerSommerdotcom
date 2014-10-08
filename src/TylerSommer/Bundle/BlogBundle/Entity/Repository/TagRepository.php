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
        if ($this->_entityName === 'TylerSommer\Bundle\BlogBundle\Entity\Category') {
            $join = 'JOIN posts_categories pt ON pt.category_id = t.id ';
        } else {
            $join = 'JOIN posts_tags pt ON pt.tag_id = t.id ';
        }
        
        $stmt = $this->_em->getConnection()->executeQuery(
            "SELECT t.id FROM tags t 
                  {$join}
                  JOIN posts p ON pt.post_id = p.id 
                WHERE p.active = TRUE 
                  AND t.active = TRUE
                GROUP BY t.id
                ORDER BY COUNT(p.id) DESC
                LIMIT 10"
        );
        $results = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        
        return $this->createQueryBuilder('t')
            ->join('TylerSommerBlogBundle:AbstractPost', 'p')
            ->where('t.id IN (:ids)')
            ->setParameter('ids', $results)
            ->getQuery()
            ->getResult();
    }
}
