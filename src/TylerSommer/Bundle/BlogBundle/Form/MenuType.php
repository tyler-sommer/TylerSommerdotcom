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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TylerSommer\Bundle\BlogBundle\Entity\Menu;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', 'choice', array(
                'choices' => array(
                    // TODO: Use something to get available menus
                    'simple_menu' => 'Simple Menu'
                )
            ))
            ->add('definition', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'type' => new MenuItemType()
            ))
            ->addEventListener(FormEvents::POST_BIND, function(FormEvent $event) {
                $menu = $event->getData();

                if (! ($menu instanceof Menu)) {
                    return;
                }

                $menu->setDefinition(array_values($menu->getDefinition()));
            });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TylerSommer\Bundle\BlogBundle\Entity\Menu'
        ));
    }

    public function getName()
    {
        return 'menu';
    }
}
