<?php

namespace TylerSommer\Bundle\BlogBundle\Controller;

use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use TylerSommer\Bundle\BlogBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends Controller
{
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
            $entity = $em->getRepository('TylerSommerBlogBundle:Page')->findOneBy(array('active' => true, 'slug' => $slug));

            if (!$entity) {
                $entity = $em->getRepository('TylerSommerBlogBundle:Post')->findOneBy(array('active' => true, 'slug' => $slug));

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to locate content');
                }
            }

            return $this->render('TylerSommerBlogBundle:Home:index.html.twig', array('entities' => array($entity)));
        } else {
            $entities = $em->getRepository('TylerSommerBlogBundle:Post')->findBy(array('active' => true), array('id' => 'DESC'));

            return $this->render('TylerSommerBlogBundle:Home:index.html.twig', array('entities' => $entities));
        }
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
