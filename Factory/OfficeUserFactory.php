<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Doctrine\ORM\EntityManager;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;

class OfficeUserFactory implements OfficeUserFactoryInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $groupRepository;

    public function __construct(EncoderFactoryInterface $encoderFactory, EntityManager $entityManager)
    {
        $this->encoderFactory  = $encoderFactory;
        $this->groupRepository = $entityManager->getRepository('OrkestraApplicationBundle:Group');
    }

    /**
     * Creates a new user assigned to the given office
     *
     * @param Office $office
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password An optional password, one should be generated if none is passed
     * @param array  $roles    An array of role names to be added as groups
     *
     * @return OfficeUser
     */
    public function createUser(Office $office, $firstName, $lastName, $username, $password = null, array $roles = array())
    {
        $password = $password ?: $this->generatePassword();

        $user = new User();
        $officeUser = new OfficeUser($office, $user);

        $encoder = $this->encoderFactory->getEncoder($user);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setUsername($username);
        $user->setPassword($encoder->encodePassword($password, $user->getSalt()));

        foreach ($roles as $role) {
            $group = $this->groupRepository->findOneBy(array('role' => $role));

            if ($group) {
                $user->addGroup($group);
            }
        }

        return $officeUser;
    }

    private function generatePassword()
    {
        return hash('sha256', uniqid(mt_rand(), true));
    }
}
