<?php

namespace Terramar\Bundle\SalesBundle\Helper;

use Doctrine\ORM\EntityManager;
use Terramar\Bundle\CustomerBundle\Entity\Customer;
use Terramar\Bundle\CustomerBundle\Entity\Customer\CustomerStatus;
use Terramar\Bundle\CustomerBundle\Helper\CustomerHelper as BaseHelper;

class CustomerHelper extends BaseHelper
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Constructor
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Cancels a customer
     *
     * This method overrides to update the associated CustomerUser
     *
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param bool $persist If true, this method will persist the CustomerUser entity
     */
    public function deactivateCustomer(Customer $customer, $persist = true)
    {
        parent::deactivateCustomer($customer);

        $this->changeCustomerStatus($customer, false, $persist);
    }

    /**
     * Activates a customer
     *
     * This method overrides to update the associated CustomerUser
     *
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param bool $persist If true, this method will persist the CustomerUser entity
     */
    public function activateCustomer(Customer $customer, $persist = true)
    {
        parent::activateCustomer($customer);

        $this->changeCustomerStatus($customer, true, $persist);
    }

    /**
     * @param \Terramar\Bundle\CustomerBundle\Entity\Customer $customer
     * @param bool $active
     * @param bool $persist
     */
    private function changeCustomerStatus(Customer $customer, $active, $persist = true)
    {
        $customerUser = $this->entityManager->getRepository('TerramarSalesBundle:CustomerUser')->findOneBy(array('customer' => $customer));

        if (!$customerUser) {
            return;
        }

        $customerUser->getUser()->setActive($active);

        if (true === $persist) {
            $this->entityManager->persist($customerUser);
        }
    }
}
