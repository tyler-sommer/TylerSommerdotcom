<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Model\MenuBuilder;

/**
 * A simple menu builder
 */
class SimpleMenuBuilder implements MenuBuilderInterface
{
    /**
     * Get the name of this menu builder
     *
     * @return string
     */
    public function getName()
    {
        return 'simple_menu';
    }

    /**
     * Get the name of the template to be used for this builder
     *
     * @return string
     */
    public function getTemplateName()
    {
        return 'TylerSommerBlogBundle:Menu:simple.html.twig';
    }
}
