<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Controller;

use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Veonik\Bundle\BlogBundle\Entity\AbstractPost;
use Veonik\Bundle\BlogBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
    /**
     * @Route("/index.json", name="home_json", defaults={"slug"=null, "_format"="json"})
     * @Route("/{slug}.json", name="page_or_post_json", defaults={"_format"="json"})
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        if ($slug) {
            $entity = $em->getRepository('VeonikBlogBundle:AbstractPost')->findOneBySlug($slug);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to locate content');
            }

            $entities = array($entity);

        } else {
            $entities = $em->getRepository('VeonikBlogBundle:Post')->findForHome();
        }

        $data = array_map(function($postJson) {
            $postJson["href"] = $this->generateUrl('page_or_post', array('slug' => $postJson['slug']), true);
            unset($postJson['slug']);

            return $postJson;
        }, array_map(function(AbstractPost $post) {
            return $post->jsonSerialize();
        }, $entities));

        if (count($entities) == 1) {
            $data = $entities[0];
        }
        return new JsonResponse($data);
    }
}
