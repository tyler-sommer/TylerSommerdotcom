<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

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
