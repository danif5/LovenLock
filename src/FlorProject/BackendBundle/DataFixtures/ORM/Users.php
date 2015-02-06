<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Gandalf_X
 * Date: 9/11/14
 * Time: 05:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace FlorProject\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FlorProject\BackendBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Users extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface  {


    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $root = $userManager->createUser();
        $root->setUsername("admin");
        $root->setPlainPassword("l0v3Nl0ck4");
        $root->setEmail("admin@gmail.com");
        $root->setEnabled(true);
        $root->setFirstName("Super");
        $root->setLastName("Administrador");
        $root->setCountry("Cuba");
        $root->setRoles( array("ROLE_ADMIN"));
        $root->setPhoto("photo1.jpg");
        $userManager->updateUser($root, true);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}