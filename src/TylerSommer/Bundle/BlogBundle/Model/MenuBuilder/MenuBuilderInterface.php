<?php

namespace TylerSommer\Bundle\BlogBundle\Model\MenuBuilder;

interface MenuBuilderInterface
{
    /**
     * Get the name of this menu builder
     *
     * This name should be unique.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the name of the template to be used for this builder
     *
     * @return string
     */
    public function getTemplateName();
}
