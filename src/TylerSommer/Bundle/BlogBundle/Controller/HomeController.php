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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use TylerSommer\Bundle\BlogBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
    /**
     * @Route("/feed", name="feed")
     */
    public function feedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('TylerSommerBlogBundle:Post')->findForHome();

        $feed = new Feed();
        $channel = new Channel();
        $channel->title('Tyler Sommer dot com')
            ->description('Tyler Sommer\'s mark on the internets')
            ->copyright(date('Y') . ' Tyler Sommer')
            ->url($this->generateUrl('home', array(), UrlGeneratorInterface::ABSOLUTE_URL))
            ->appendTo($feed);

        foreach ($entities as $post) {
            $item = new Item();
            $item->title($post->getTitle())
                ->pubDate($post->getDatePublished()->getTimestamp())
                ->url($this->generateUrl('page_or_post', array('slug' => $post->getSlug()), UrlGeneratorInterface::ABSOLUTE_URL))
                ->appendTo($channel);
        }

        return new Response($feed->render(), 200, array('Content-type' => 'application/xml'));
    }

    /**
     * @Route("/", name="home", defaults={"slug"=null})
     * @Route("/{slug}", name="page_or_post")
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        if ($slug) {
            $entity = $em->getRepository('TylerSommerBlogBundle:AbstractPost')->findOneBySlug($slug);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to locate content');
            }

            $entities = array($entity);

        } else {
            $entities = $em->getRepository('TylerSommerBlogBundle:Post')->findForHome();
        }

        return $this->render('TylerSommerBlogBundle:Home:index.html.twig', array('entities' => $entities));
    }

    /**
     * @Template()
     */
    public function rightSideAction()
    {
        $posts = $this->getRepository('TylerSommerBlogBundle:Post')->createQueryBuilder('p')
            ->where('p.datePublished >= :dateStart')
            ->andWhere('p.active = true')
            ->setParameters(array(
                'dateStart' => new \DateTime('-1 year')
            ))
            ->orderBy('p.datePublished', 'DESC')
            ->getQuery()->setMaxResults(10)->getResult();
        $tags = $this->getRepository('TylerSommerBlogBundle:Tag')->getSidebarData();
        $categories = $this->getRepository('TylerSommerBlogBundle:Category')->getSidebarData();
        $form = $this->createForm(new SearchType());

        $archive = array();
        foreach ($posts as $post) {
            $month = $post->getDatePublished()->format('F Y');
            if (!isset($archive[$month])) {
                $archive[$month] = array();
            }

            $archive[$month][] = $post;
        }

        return array(
            'archive' => $archive,
            'tags' => $tags,
            'categories' => $categories,
            'form' => $form->createView()
        );
    }

    /**
     * Outputs a file
     *
     * @Route("/file/{id}/view", name="file_view")
     */
    public function viewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $file \Orkestra\Bundle\ApplicationBundle\Entity\File */
        $file = $em->find('Orkestra\Bundle\ApplicationBundle\Entity\File', $id);

        if (!$file || !file_exists($file->getPath())) {
            throw $this->createNotFoundException('Unable to locate File');
        }

        $securityContext = $this->get('security.context');

        foreach ($file->getGroups() as $group) {
            if (!$securityContext->isGranted($group->getRole())) {
                throw $this->createNotFoundException('Unable to locate File');
            }
        }

        $response = new Response();
        $response->setLastModified(new \DateTime('@' . filemtime($file->getPath())));
        $response->setPublic();
        if (($hash = $file->getMd5()) !== '') {
            $response->setEtag($hash);
        }

        if ($response->isNotModified($request)) {
            return $response;
        }

        $response->setContent($file->getContent());
        $response->headers->add(array(
                'Content-Type' => $file->getMimeType(),
            ));

        return $response;
    }
}
