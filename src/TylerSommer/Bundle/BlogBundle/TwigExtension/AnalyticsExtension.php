<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\TwigExtension;

use Symfony\Component\HttpFoundation\Request;
use TylerSommer\Bundle\BlogBundle\Entity\AbstractPost;
use TylerSommer\Bundle\BlogBundle\Entity\Page;
use TylerSommer\Bundle\BlogBundle\Entity\Post;

/**
 * Provides functionality to output Google analytics tracking code
 */
class AnalyticsExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $trackingId;

    /**
     * @param string $trackingId
     */
    public function __construct($trackingId)
    {
        $this->trackingId = $trackingId;
    }
    
    /**
     * @return string
     */
    public function getGaTrackingCode(\Twig_Environment $env)
    {
        $globals = $env->getGlobals();
        $userid = ($user = $globals['app']->getUser()) ? $user->getId() : null;
        
        $userLine = $userid ? "ga('set', '&uid', {$userid});\n" : "";
        
        return <<<EOF
<script type="text/javascript">
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '{$this->trackingId}', 'auto');
{$userLine}ga('require', 'linkid', 'linkid.js');
ga('send', 'pageview');
</script>
EOF;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'ga_tracking', 
                array($this, 'getGaTrackingCode'), 
                array(
                    'is_safe'           => array('html'),
                    'needs_environment' => true
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
        return 'analytics';
    }
}
