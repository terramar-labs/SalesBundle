<?php

namespace TerraMar\Bundle\SalesBundle\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * Makes any changes necessary to class metadata
 */
class ClassMetadataSubscriber implements EventSubscriber
{
    /**
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $metadata = $event->getClassMetadata();

        // Sets the Customer entity's repositoryClass to the SalesBundle's implementation
        if ($metadata->getName() === 'TerraMar\Bundle\CustomerBundle\Entity\Customer') {
            $metadata->setCustomRepositoryClass('TerraMar\Bundle\SalesBundle\Repository\CustomerRepository');
        }
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::loadClassMetadata);
    }
}
