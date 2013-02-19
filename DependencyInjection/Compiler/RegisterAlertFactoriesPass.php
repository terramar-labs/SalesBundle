<?php

namespace Terramar\Bundle\SalesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterAlertFactoriesPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('terramar.sales.factory.alert')) {
            return;
        }

        $definition = $container->getDefinition('terramar.sales.factory.alert');

        foreach ($container->findTaggedServiceIds('terramar.assigned_alert_factory') as $serviceId => $tags) {
            $definition->addMethodCall('registerAssignedAlertFactory', array(new Reference($serviceId)));
        }
    }
}
