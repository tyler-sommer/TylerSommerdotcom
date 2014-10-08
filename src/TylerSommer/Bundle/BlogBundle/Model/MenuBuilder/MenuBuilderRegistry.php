<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Model\MenuBuilder;

class MenuBuilderRegistry
{
    /**
     * @var array|MenuBuilderInterface[]
     */
    private $builders = array();

    /**
     * @param MenuBuilderInterface $builder
     */
    public function registerBuilder(MenuBuilderInterface $builder)
    {
        $this->builders[$builder->getName()] = $builder;
    }

    /**
     * @param $name
     *
     * @return MenuBuilderInterface
     */
    public function getBuilder($name)
    {
        if (!isset($this->builders[$name])) {
            throw new \RuntimeException(sprintf('Unknown or unregistered menu builder "%s"', $name));
        }

        return $this->builders[$name];
    }

    /**
     * @return array|MenuBuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }
}
