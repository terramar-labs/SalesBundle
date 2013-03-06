<?php

namespace Terramar\Bundle\SalesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Registers all workers with the Worker factory
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
        $this->overrideCustomerHelper($container);
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
