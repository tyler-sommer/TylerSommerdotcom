<?php

namespace TylerSommer\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TylerSommer\Bundle\BlogBundle\Form\Transformer\MenuDefinitionTransformer;

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
            ));

        $subBuilder = $builder->getFormFactory()
            ->createNamedBuilder('definition', 'collection', null, array(
                'type' => new MenuItemType()
            ))
            ->addModelTransformer(new MenuDefinitionTransformer());

        $builder->add($subBuilder);
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
