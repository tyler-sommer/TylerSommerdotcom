<?php

namespace TylerSommer\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('body', 'ckeditor')
            ->add('author')
            ->add('active', null, array('required' => false, 'label' => 'Publish'))
            ->add('tags', 'tag', array(
                'class' => 'TylerSommer\Bundle\BlogBundle\Entity\Tag',
                'required' => false
            ))
            ->add('categories', 'tag', array(
                'class' => 'TylerSommer\Bundle\BlogBundle\Entity\Category',
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TylerSommer\Bundle\BlogBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'post';
    }
}
