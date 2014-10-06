<?php

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
            ->add('route', 'text')
            ->add('icon', 'text', array('required' => false))
            ->add('role', 'text', array('required' => false))
            ->add('not_role', 'text', array('required' => false));
    }

    public function getName()
    {
        return 'menuitem';
    }
}
