<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', 'text')
            ->add('route', 'text', array('label' => 'Target', 'required' => false))
            ->add('type', 'choice', array('choices' => array(
                    'route' => 'Route-based',
                    'page' => 'Slug-based',
                    'group' => 'Group Title'
                )))
            ->add('icon', 'text', array('required' => false))
            ->add('role', 'text', array('required' => false))
            ->add('not_role', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'menuitem';
    }
}
