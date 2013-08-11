<?php

namespace TylerSommer\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;
use TylerSommer\Bundle\BlogBundle\Model\MenuBuilder\MenuBuilderInterface;

/**
 * A menu
 *
 * @ORM\Entity
 * @ORM\Table(name="menus")
 */
class Menu extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="definition", type="string")
     */
    protected $definition;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    protected $type;

    /**
     * @var MenuBuilderInterface
     */
    private $builder;

    /**
     * @param string $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
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

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param MenuBuilderInterface $builder
     */
    public function setBuilder(MenuBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return MenuBuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }
}
