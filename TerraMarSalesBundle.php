<?php

namespace TerraMar\Bundle\SalesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use TerraMar\Bundle\SalesBundle\DependencyInjection\Compiler\RegisterAlertFactoriesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TerraMarSalesBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterAlertFactoriesPass());
    }
}
