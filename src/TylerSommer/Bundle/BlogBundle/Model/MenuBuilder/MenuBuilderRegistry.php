<?php

namespace TylerSommer\Bundle\BlogBundle\Model\MenuBuilder;

class MenuBuilderRegistry
{
    private $builders = array();

    public function registerBuilder(MenuBuilderInterface $builder)
    {
        $this->builders[$builder->getName()] = $builder;
    }

    public function getBuilder($name)
    {
        if (!isset($this->builders[$name])) {
            throw new \RuntimeException(sprintf('Unknown or unregistered menu builder "%s"', $name));
        }

        return $this->builders[$name];
    }
}
