<?php

namespace TylerSommer\Bundle\BlogBundle\Controller;

use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
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
     * @Route("/about", name="about", defaults={"slug"="about"})
     * @Route("/contact", name="contact", defaults={"slug"="contact"})
     * @Route("/{slug}", name="page_or_post")
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        if ($slug) {
            $entity = $em->getRepository('TylerSommerBlogBundle:Page')->findOneBySlug($slug);
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
            ->setParameters(array(
                'dateStart' => new \DateTime('-1 year')
            ))
            ->orderBy('p.datePublished', 'DESC')
            ->getQuery()->getResult();
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
}
