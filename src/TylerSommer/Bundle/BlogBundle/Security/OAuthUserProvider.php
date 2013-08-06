<?php

namespace TylerSommer\Bundle\BlogBundle\Security;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use TylerSommer\Bundle\BlogBundle\Entity\Author;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

class OAuthUserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Repository\UserRepository
     */
    protected $repository;

    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('OrkestraApplicationBundle:User');
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Creates a new user from an OAuth response
     *
     * @todo Should this flush?
     *
     * @param \HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface $response
     *
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\User
     * @throws \RuntimeException
     */
    protected function createUserFromResponse(UserResponseInterface $response)
    {
        if (!$response->getUsername()) {
            // TODO: Enumerate response errors?
            throw new \RuntimeException('Unable to authenticate. An error occurred during OAuth authentication.');
        }

        $group = $this->entityManager->getRepository('OrkestraApplicationBundle:Group')->findOneBy(array('role' => 'ROLE_USER'));

        if (!$group) {
            throw new \RuntimeException('Unable to locate user group with role "ROLE_USER".');
        }

        $user = new User();
        $user->setUsername($response->getUsername());
        list ($firstName, $lastName) = explode(' ', $response->getRealName(), 2);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setGroups($group);

        $factory = $this->encoderFactory->getEncoder($user);
        $user->setPassword($factory->encodePassword(md5(uniqid(mt_rand(), true)), $user->getSalt()));

        $author = new Author();
        $author->setUser($user);

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @param \HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface $response
     *
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\User
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            $user = $this->loadUserByUsername($response->getUsername());
        } catch (UsernameNotFoundException $e) {
            $user = $this->createUserFromResponse($response);
        }

        return $user;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Orkestra\Bundle\ApplicationBundle\Entity\User';
    }

    /**
     * @param string $username
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->repository->loadUserByUsername($username);

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->repository->refreshUser($user);
    }
}
