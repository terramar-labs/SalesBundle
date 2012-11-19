Overview
========

This bundle provides contractual sales management.



Installation
------------

1. Install via composer



Configuration
-------------

1. Add bundles routes in routing.yml

    TerraMarSalesBundle:
      resource: "@TerraMarSalesBundle/Resources/config/routing.yml"

2. Register the DBAL types in config.yml

    doctrine:
      dbal:
        types:
          enum.terramar.sales.contract_status:     TerraMar\Bundle\SalesBundle\DbalType\ContractStatusEnumType
          enum.terramar.sales.found_by_type:       TerraMar\Bundle\SalesBundle\DbalType\FoundByTypeEnumType
          enum.terramar.sales.billing_frequency:   TerraMar\Bundle\SalesBundle\DbalType\BillingFrequencyEnumType
          enum.terramar.sales.invoice_status:      TerraMar\Bundle\SalesBundle\DbalType\InvoiceStatusEnumType
          enum.terramar.sales.alert_status:        TerraMar\Bundle\SalesBundle\DbalType\AlertStatusEnumType
          enum.terramar.sales.alert_priority:      TerraMar\Bundle\SalesBundle\DbalType\AlertPriorityEnumType
          enum.terramar.sales.alert_type:          TerraMar\Bundle\SalesBundle\DbalType\AlertTypeEnumType

3. Ensure the proper roles are in the hierarchy in security.yml. See ROLES REFERENCE below.


Javascript Dependencies
-----------------------

This project, by default, requires a few javascript dependencies, which are
included in the public/js folder.

Note that these assume your project uses jQuery (and bootstrap, for the matter).

This includes:

* jQuery Signature Pad http://thomasjbradley.ca/lab/signature-pad/
* Underscore.js http://documentcloud.github.com/underscore/


Configuring using assetic:

    {% javascripts 'bundles/terramarsales/js/*.js' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}



Roles Reference
---------------

*ROLE_COMPANY_WRITE*    - Create and edit companies and offices
*ROLE_COMPANY_READ*     - View companies and offices

*ROLE_AGREEMENT_WRITE*  - Create and edit agreements
*ROLE_AGREEMENT_READ*   - View agreements

*ROLE_CONTRACT_WRITE*   - Create and edit contracts
*ROLE_CONTRACT_READ*    - View contracts

*ROLE_INVOICE_WRITE*    - Create and edit invoices
*ROLE_INVOICE_READ*     - View invoices
*ROLE_INVOICE_SALE*     - Process (sale transaction) invoices
*ROLE_INVOICE_REFUND*   - Refund invoices

*ROLE_TACKBOARD_WRITE*  - Create and edit other users' alerts
*ROLE_TACKBOARD_READ*   - View other users' alerts



Additional Notes
----------------

This bundle overrides the metadata for the CustomerBundle's Customer entity,
changing the repositoryClass to
`TerraMar\Bundle\SalesBundle\Repository\CustomerRepository`.
