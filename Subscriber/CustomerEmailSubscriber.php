<?php

namespace TerraMar\Bundle\SalesBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TerraMar\Bundle\SalesBundle\Repository\CustomerSalesProfileRepository;
use TerraMar\Bundle\SalesBundle\Factory\CustomerUserFactory;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Updates a CustomerUser's email address
 */
class CustomerEmailSubscriber implements EventSubscriber
{
    protected $initialized = false;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Repository\CustomerSalesProfileRepository
     */
    protected $profileRepository;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Factory\CustomerUserFactory
     */
    protected $customerUserFactory;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * This method is necessary to work-around the circular reference error
     * caused by this subscriber relying on the entity manager.
     */
    private function initialize()
    {
        $this->profileRepository = $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile');
        $this->customerUserFactory = $this->container->get('terramar.sales.factory.customer_user');
    }

    /**
     * Handles the onFlush event
     *
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $event
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        if (!$this->initialized) {
            $this->initialize();
        }

        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->handle($em, $uow, $entity);
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Doctrine\ORM\UnitOfWork $uow
     * @param object $entity
     */
    private function handle(EntityManager $em, UnitOfWork $uow, $entity)
    {
        if (!$entity instanceof Customer) {
            return;
        }

        $changeSet = $uow->getEntityChangeSet($entity);
        if (!isset($changeSet['emailAddress'])) {
            return;
        }

        $profile = $this->profileRepository->findOneByCustomer($entity);
        if (!$profile) {
            return;
        }

        $this->customerUserFactory->updateCustomerEmail($profile, $entity->getEmailAddress());

        if ($profile->getUser() && ($user = $profile->getUser()->getUser())) {
            $uow->persist($user);
            $uow->computeChangeSet($em->getClassMetadata(get_class($user)), $user);
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::onFlush);
    }
}
