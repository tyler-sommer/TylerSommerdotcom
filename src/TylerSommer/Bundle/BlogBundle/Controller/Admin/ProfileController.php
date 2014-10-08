<?php

/*
 * Copyright (c) Tyler Sommer
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

/*
 * This file is part of the OrkestraApplicationBundle package.
 *
 * Copyright (c) Orkestra Community
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace TylerSommer\Bundle\BlogBundle\Controller\Admin;

use Orkestra\Bundle\ApplicationBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Orkestra\Bundle\ApplicationBundle\Form\ProfileType;
use Orkestra\Bundle\ApplicationBundle\Form\ChangePasswordType;

/**
 * Profile controller.
 *
 * @Route("/manage/profile")
 */
class ProfileController extends Controller
{
    /**
     * Shows the form  to edit the current user's profile
     *
     * @Route("/edit", name="profile_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction()
    {
        $user = $this->getUser();
        $form = $this->createForm(new ProfileType(), $user);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates the current user's profile
     *
     * @Route("/update", name="profile_update")
     * @Template("TylerSommerBlogBundle:Admin/Profile:edit.html.twig")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("POST")
     */
    public function updateAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new ProfileType(), $user);

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            try {
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->set('success', 'Your changes have been saved.');

                return $this->redirect($this->generateUrl('profile_edit'));
            } catch (\Exception $e) {
                $form->addError(new FormError('Could not save changes. If the problem persists, please contact support.'));
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Shows a form to edit the current user's password
     *
     * @Route("/password/edit", name="profile_password_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editPasswordAction()
    {
        $form = $this->createForm(new ChangePasswordType());

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Updates the current user's password
     *
     * @Route("/password/update", name="profile_password_update")
     * @Template("TylerSommerBlogBundle:Admin/Profile:editPassword.html.twig")
     * @Secure(roles="ROLE_ADMIN")
     * @Method("POST")
     */
    public function updatePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(new ChangePasswordType());

        $form->bind($request);
        $data = $form->getData();

        /** @var $factory \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface */
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $current = $encoder->encodePassword($data['current'], $user->getSalt());

        if ($current !== $user->getPassword()) {
            $form->get('current')->addError(new FormError('Current password is not correct'));
        }

        if ($form->isValid()) {
            $user->setPassword($encoder->encodePassword($data['password'], $user->getSalt()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->set('success', 'Your password has been changed.');

            return $this->redirect($this->generateUrl('profile_edit'));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
