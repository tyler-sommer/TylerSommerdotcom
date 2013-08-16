<?php

namespace TylerSommer\Bundle\BlogBundle\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use TylerSommer\Bundle\BlogBundle\Entity\Menu;

class MenuDefinitionTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return array|mixed|string
     */
    public function transform($value)
    {
        if (!is_array($value)) {
            $value = array();
        }

        foreach ($value as $label => &$definition) {
            $definition['label'] = $label;
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return array|mixed|null
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            return null;
        }

        $definitions = array();
        foreach ($value as $definition) {
            $label = $definition['label'];
            unset($definition['label']);

            $definitions[$label] = $definition;
        }

        return $definitions;
    }
}
