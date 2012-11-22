<?php

namespace TylerSommer\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\EntityBase;

/**
 * A tag
 *
 * @ORM\Entity
 * @ORM\Table(name="tags")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *   "Tag"      = "TylerSommer\Bundle\BlogBundle\Entity\Tag",
 *   "Category" = "TylerSommer\Bundle\BlogBundle\Entity\Category"
 * })
 */
abstract class AbstractTag extends EntityBase
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", unique=true)
     */
    protected $name;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
