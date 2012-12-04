<?php

namespace TerraMar\Bundle\SalesBundle\Factory;

use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\SalesBundle\Entity\CustomerUser;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Factory\CustomerUser\DuplicateEmailException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Orkestra\Bundle\ApplicationBundle\Entity\User;

class CustomerUserFactory implements CustomerUserFactoryInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Constructor
     *
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $entityManager)
    {
        $this->encoderFactory = $encoderFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new CustomerUser entity
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerUser
     * @throws \RuntimeException
     */
    public function create(CustomerSalesProfile $profile)
    {
        $office = $profile->getOffice();
        $customer = $profile->getCustomer();
        $customerEmail = $customer->getEmailAddress();

        $this->checkForDuplicateEmail($customerEmail);

        $user = new User();
        $user->setUsername($customerEmail);
        $user->setEmail($customerEmail);
        $user->setFirstName($customer->getFirstName());
        $user->setLastName($customer->getLastName());

        $group = $this->entityManager->getRepository('Orkestra\Bundle\ApplicationBundle\Entity\Group')->findOneBy(array('role' => 'ROLE_CUSTOMER'));

        if (!$group) {
            throw new \RuntimeException('Unable to find find Customer group. Does a group with the role "ROLE_CUSTOMER" exist?');
        }

        $user->addGroup($group);

        // TODO: Investigate ways to pass along a password
        $password = $this->generatePassword($profile);
        $encoder = $this->encoderFactory->getEncoder($user);

        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $customerUser = new CustomerUser($office, $customer, $user);

        return $customerUser;
    }

    /**
     * Updates a CustomerUser's email address
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     * @param string $email
     *
     * @return void
     */
    public function updateCustomerEmail(CustomerSalesProfile $profile, $email)
    {
        $this->checkForDuplicateEmail($email);

        $profile->getUser()->getUser()->setEmail($email);
        $profile->getUser()->getUser()->setUsername($email);
    }

    /**
     * Checks to ensure the given email is unique
     *
     * @param string $email
     *
     * @throws \TerraMar\Bundle\SalesBundle\Factory\CustomerUser\DuplicateEmailException
     */
    protected function checkForDuplicateEmail($email)
    {
        $duplicateEmail = $this->entityManager->getRepository('Orkestra\Bundle\ApplicationBundle\Entity\User')->findOneBy(array('email' => $email));

        if ($duplicateEmail) {
            throw new DuplicateEmailException();
        }
    }

    /**
     * Generates a password for the given Customer
     *
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     *
     * @return string
     */
    protected function generatePassword(CustomerSalesProfile $profile)
    {
        if (($address = $profile->getCustomer()->getBillingAddress()) === null) {
            $address = $profile->getCustomer()->getContactAddress();
        }

        return $address->getPostalCode();
    }
}