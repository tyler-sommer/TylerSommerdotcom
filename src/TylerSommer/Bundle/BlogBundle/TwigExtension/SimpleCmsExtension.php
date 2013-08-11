<?php

namespace TylerSommer\Bundle\BlogBundle\TwigExtension;

/**
 * Provides useful basic CMS functionality within Twig
 */
class SimpleCmsExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Loader_String
     */
    private $stringLoader;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stringLoader = new \Twig_Loader_String();
    }

    /**
     * Renders raw Twig
     *
     * @param \Twig_Environment $environment
     * @param string $template The raw twig template
     * @param array $parameters
     *
     * @return string
     */
    public function renderTwig(\Twig_Environment $environment, $template, array $parameters = array())
    {
        $template = $this->loadTemplate($environment, $template);

        return $template->render($parameters);
    }

    /**
     * Loads raw Twig using the given Environment
     *
     * @param \Twig_Environment $environment
     * @param string $template The raw twig template
     *
     * @return \Twig_TemplateInterface
     */
    private function loadTemplate(\Twig_Environment $environment, $template)
    {
        $existingLoader = $environment->getLoader();
        $environment->setLoader($this->stringLoader);

        $template = $environment->loadTemplate($template);

        $environment->setLoader($existingLoader);

        return $template;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'render_twig' => new \Twig_Function_Method(
                $this,
                'renderTwig',
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html')
                )
            ),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'simple_cms';
    }
}
