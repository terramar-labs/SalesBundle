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
        $this->registerTypeIfNotRegistered('enum.terramar.sales.contract_status',    'Terramar\Bundle\SalesBundle\DbalType\ContractStatusEnumType');
        $this->registerTypeIfNotRegistered('enum.terramar.sales.found_by_type',      'Terramar\Bundle\SalesBundle\DbalType\FoundByTypeEnumType');

        $this->registerTypeIfNotRegistered('enum.terramar.sales.billing_frequency',  'Terramar\Bundle\SalesBundle\DbalType\BillingFrequencyEnumType');
        $this->registerTypeIfNotRegistered('enum.terramar.sales.invoice_status',     'Terramar\Bundle\SalesBundle\DbalType\InvoiceStatusEnumType');
        $this->registerTypeIfNotRegistered('enum.terramar.sales.alert_status',       'Terramar\Bundle\SalesBundle\DbalType\AlertStatusEnumType');
        $this->registerTypeIfNotRegistered('enum.terramar.sales.alert_priority',     'Terramar\Bundle\SalesBundle\DbalType\AlertPriorityEnumType');
        $this->registerTypeIfNotRegistered('enum.terramar.sales.alert_type',         'Terramar\Bundle\SalesBundle\DbalType\AlertTypeEnumType');
        $this->registerTypeIfNotRegistered('enum.terramar.sales.invoice_type',       'Terramar\Bundle\SalesBundle\DbalType\InvoiceTypeEnumType');
    }

    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterAlertFactoriesPass());
    }

    /**
     * Registers a type with Doctrine DBAL if it is not already registered.
     *
     * This is necessary because multiple instantiations of this bundle will
     * cause an error to be thrown by the DBAL.
     *
     * @param string $typeName
     * @param string $className
     */
    private function registerTypeIfNotRegistered($typeName, $className)
    {
        if (!(Type::hasType($typeName))) {
            Type::addType($typeName, $className);
        }
    }
}
