<?php

namespace Terramar\Bundle\SalesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;
use Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Creates an office and assigns the 'Administrator' user to it
 *
 * This fixture is intended to be run after Orkestra's ApplicationBundle's
 * fixtures are ran.
 */
class InitialOfficeData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var $factory \Terramar\Bundle\SalesBundle\Factory\OfficeFactoryInterface */
        $factory = $this->container->get('terramar.sales.factory.office');

        $office = new Office();
        $office->setName('Default Company');
        $office->setContactName('Technical Support');

        $address = new Address();
        $address->setPhone('(555) 555-1234');
        $address->setStreet('55 Main St');
        $address->setCity('Carlsbad');
        $address->setRegion($manager->getRepository('OrkestraApplicationBundle:Contact\\Region')->findOneBy(array('name' => 'California')));
        $address->setPostalCode('92011');

        $office->setContactAddress($address);
        $factory->buildOffice($office);

        $manager->persist($office);

        $officeUser = new OfficeUser($office, $this->getReference('admin-user'));

        $manager->persist($officeUser);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * Sets the Container
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
