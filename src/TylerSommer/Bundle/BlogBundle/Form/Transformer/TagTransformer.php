<?php

namespace TylerSommer\Bundle\BlogBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\Util\PropertyPath;
use Doctrine\ORM\EntityManager;

class TagTransformer implements DataTransformerInterface
{
    protected $repository;

    protected $entityManager;

    protected $class;

    protected $property;

    protected $propertyPath;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param string $class
     * @param string $property
     */
    public function __construct(EntityManager $entityManager, $class, $property = 'name')
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($class);
        $this->class = $class;
        $this->property = $property;
        $this->propertyPath = new PropertyPath($this->property);
    }

    /**
     * @param mixed $value
     *
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     * @return string
     */
    public function transform($value)
    {
        if (null === $value) {
            return '';
        } elseif ($value instanceof Collection) {
            $values = $value->toArray();
        } elseif (is_array($value)) {
            $values = $value;
        } else {
            throw new TransformationFailedException('TagTransformer expects to transform an array or Collection value');
        }

        $propertyPath = $this->propertyPath;
        $values = array_map(function($value) use ($propertyPath) {
            return $propertyPath->getValue($value);
        }, $values);

        return implode(',', $values);
    }

    /**
     * @param mixed $value
     *
     * @return array|null
     */
    public function reverseTransform($value)
    {
        if (trim($value) == '') {
            return null;
        }

        $names = explode(',', $value);
        $class = $this->class;

        $tags = array();
        foreach ($names as $name) {
            $entity = $this->repository->findOneBy(array($this->property => $name));
            if (!$entity) {
                $entity = new $class();
                $this->propertyPath->setValue($entity, $name);
            }

            $tags[] = $entity;
        }

        return $tags;
    }
}
