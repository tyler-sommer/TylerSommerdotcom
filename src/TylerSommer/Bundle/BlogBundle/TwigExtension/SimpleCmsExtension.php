<?php

namespace TylerSommer\Bundle\BlogBundle\TwigExtension;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use TylerSommer\Bundle\BlogBundle\Entity\Menu;

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
     * @var EntityRepository
     */
    private $menuRepository;

    /**
     * Constructor
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->stringLoader = new \Twig_Loader_String();

        $this->menuRepository = $entityManager->getRepository('TylerSommerBlogBundle:Menu');
    }

    /**
     * Renders a menu
     *
     * @param \Twig_Environment $environment
     * @param string $name The name of the menu
     *
     * @return string
     */
    public function renderMenu(\Twig_Environment $environment, $name)
    {
        $menu = $this->loadMenu($name);
        $builder = $menu->getBuilder();

        return $environment->render($builder->getTemplateName(), array('menu' => $menu));
    }

    /**
     * Loads a Menu entity, given its name
     *
     * @param string $name
     *
     * @return Menu
     * @throws \RuntimeException
     */
    private function loadMenu($name)
    {
        $menu = $this->menuRepository->findOneBy(array('name' => $name));

        if (!$menu) {
            throw new \RuntimeException(sprintf('Unable to load menu "%s"', $name));
        }

        return $menu;
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
            'render_menu' => new \Twig_Function_Method(
                $this,
                'renderMenu',
                array(
                    'needs_environment' => true,
                    'is_safe' => array('html')
                )
            )
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
