<?php

namespace TerraMar\Bundle\SalesBundle\Helper;

use TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile;
use TerraMar\Bundle\CustomerBundle\Entity\Customer;
use TerraMar\Bundle\SalesBundle\Helper\CustomerUser\DuplicateEmailException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Orkestra\Bundle\ApplicationBundle\Entity\Group;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use TerraMar\Bundle\SalesBundle\Entity\OfficeUser;

class CustomerUserHelper
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
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface $encoderFactory
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $entityManager)
    {
        $this->encoderFactory = $encoderFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     *
     * @return \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     * @throws \RuntimeException
     */
    public function createCustomerUser(CustomerSalesProfile $profile)
    {

        $office = $profile->getOffice();
        $customerEmail = $profile->getCustomer()->getEmailAddress();

        $this->checkForDuplicateEmail($customerEmail);

        $user = new User();
        $user->setUsername($customerEmail);
        $user->setEmail($customerEmail);
        $user->setFirstName($profile->getCustomer()->getFirstName());
        $user->setLastName($profile->getCustomer()->getLastName());

        $group = $this->entityManager->getRepository('Orkestra\Bundle\ApplicationBundle\Entity\Group')->findOneBy(array('role' => 'ROLE_CUSTOMER'));

        if (!$group) {
            throw new \RuntimeException('Could not Find Customer Role Group') ;
        }

        $user->addGroup($group);

        $password = $profile->getCustomer()->getBillingAddress()->getPostalCode();
        $encoder = $this->encoderFactory->getEncoder($user);

        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        $officeUser = new OfficeUser($office, $user);

        return $officeUser;
    }

    public function updateCustomerEmail(CustomerSalesProfile $profile, $email)
    {
        $this->checkForDuplicateEmail($email);

        $profile->getUser()->getUser()->setEmail($email);
        $profile->getUser()->getUser()->setUsername($email);
    }

    /**
     * @param $email
     *
     * @throws CustomerUser\DuplicateEmailException
     */
    private function checkForDuplicateEmail($email)
    {
        $duplicateEmail = $this->entityManager->getRepository('TerraMar\Bundle\CustomerBundle\Entity\Customer')->findOneBy(array('emailAddress' => $email));

        if ($duplicateEmail) {
            throw new DuplicateEmailException();
        }
    }
}
