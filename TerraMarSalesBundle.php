<?php

namespace TerraMar\Bundle\SalesBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TerraMar\Bundle\SalesBundle\DependencyInjection\Compiler\RegisterAlertFactoriesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TerraMarSalesBundle extends Bundle
{
    public function boot()
    {
        Type::addType('enum.terramar.sales.contract_status',    'TerraMar\Bundle\SalesBundle\DbalType\ContractStatusEnumType');
        Type::addType('enum.terramar.sales.found_by_type',      'TerraMar\Bundle\SalesBundle\DbalType\FoundByTypeEnumType');
        Type::addType('enum.terramar.sales.billing_frequency',  'TerraMar\Bundle\SalesBundle\DbalType\BillingFrequencyEnumType');
        Type::addType('enum.terramar.sales.invoice_status',     'TerraMar\Bundle\SalesBundle\DbalType\InvoiceStatusEnumType');
        Type::addType('enum.terramar.sales.alert_status',       'TerraMar\Bundle\SalesBundle\DbalType\AlertStatusEnumType');
        Type::addType('enum.terramar.sales.alert_priority',     'TerraMar\Bundle\SalesBundle\DbalType\AlertPriorityEnumType');
        Type::addType('enum.terramar.sales.alert_type',         'TerraMar\Bundle\SalesBundle\DbalType\AlertTypeEnumType');
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterAlertFactoriesPass());
    }
}
