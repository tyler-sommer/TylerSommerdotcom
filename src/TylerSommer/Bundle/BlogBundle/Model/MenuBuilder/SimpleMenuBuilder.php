<?php

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
