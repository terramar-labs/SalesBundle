<?php

namespace Terramar\Bundle\SalesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Overrides the necessary service definitions
 *
 * This exists as a Compiler Pass to ensure that the proper things get
 * overridden and that, as much as possible, order is not an issue
 */
class OverrideServiceDefinitionsPass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $this->overrideAlertFactory($container);
        $this->overrideCustomerHelper($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function overrideAlertFactory(ContainerBuilder $container)
    {
        $container->setParameter('terramar.notification.factory.alert.class', 'Terramar\Bundle\SalesBundle\Factory\AlertFactory');
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function overrideCustomerHelper(ContainerBuilder $container)
    {
        $container->setParameter('terramar.customer.helper.customer.class', 'Terramar\Bundle\SalesBundle\Helper\CustomerHelper');
        $definition = $container->getDefinition('terramar.customer.helper.customer');
        $definition->addArgument(new Reference('doctrine.orm.entity_manager'));
    }
}
