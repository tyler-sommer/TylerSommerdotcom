<?php

namespace TylerSommer\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('body', 'ckeditor')
            ->add('author')
            ->add('enableComments', 'checkbox', array('required' => false))
            ->add('active', null, array('required' => false, 'label' => 'Publish'))
            ->add('tags', 'select2_tag', array(
                'class' => 'TylerSommer\Bundle\BlogBundle\Entity\Tag',
                'required' => false
            ))
            ->add('categories', 'select2_tag', array(
                'class' => 'TylerSommer\Bundle\BlogBundle\Entity\Category',
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TylerSommer\Bundle\BlogBundle\Entity\Page'
        ));
    }

    public function getName()
    {
        return 'post';
    }
}
