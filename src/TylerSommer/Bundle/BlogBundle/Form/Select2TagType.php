<?php

namespace TylerSommer\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use TylerSommer\Bundle\BlogBundle\Form\Transformer\TagTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Select2TagType extends AbstractType
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new TagTransformer($this->entityManager, $options['class'], $options['property']));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['values'] = $options['values'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'class'
        ));

        $entityManager = $this->entityManager;
        $propertyAccessor = PropertyAccess::getPropertyAccessor();

        $resolver->setDefaults(array(
            'property' => 'name',
            'values' => function(Options $options) use ($entityManager, $propertyAccessor) {
                $propertyPath = $options['property'];
                $class = $options['class'];

                $repository = $entityManager->getRepository($class);

                $entities = $repository->createQueryBuilder('t')
                    ->where('t INSTANCE OF ' . $class)
                    ->andWhere('t.active = true')
                    ->getQuery()
                    ->getResult();

                $values = array_map(function($value) use ($propertyAccessor, $propertyPath) {
                    return $propertyAccessor->getValue($value, $propertyPath);
                }, $entities);

                return implode(',', $values);
            }
        ));
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'select2_tag';
    }
}
