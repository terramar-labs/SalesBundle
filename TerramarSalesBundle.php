<?php

namespace Terramar\Bundle\SalesBundle;

use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Terramar\Bundle\SalesBundle\DependencyInjection\Compiler\RegisterAlertFactoriesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TerramarSalesBundle extends Bundle
{
    public function boot()
    {
        Type::addType('enum.terramar.sales.contract_status',    'Terramar\Bundle\SalesBundle\DbalType\ContractStatusEnumType');
        Type::addType('enum.terramar.sales.found_by_type',      'Terramar\Bundle\SalesBundle\DbalType\FoundByTypeEnumType');
        Type::addType('enum.terramar.sales.billing_frequency',  'Terramar\Bundle\SalesBundle\DbalType\BillingFrequencyEnumType');
        Type::addType('enum.terramar.sales.invoice_status',     'Terramar\Bundle\SalesBundle\DbalType\InvoiceStatusEnumType');
        Type::addType('enum.terramar.sales.alert_status',       'Terramar\Bundle\SalesBundle\DbalType\AlertStatusEnumType');
        Type::addType('enum.terramar.sales.alert_priority',     'Terramar\Bundle\SalesBundle\DbalType\AlertPriorityEnumType');
        Type::addType('enum.terramar.sales.alert_type',         'Terramar\Bundle\SalesBundle\DbalType\AlertTypeEnumType');
        Type::addType('enum.terramar.sales.invoice_type',       'Terramar\Bundle\SalesBundle\DbalType\InvoiceTypeEnumType');
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterAlertFactoriesPass());
    }
}
