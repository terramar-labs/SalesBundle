<?php

namespace Terramar\Bundle\SalesBundle\Factory;

use Doctrine\ORM\EntityManager;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Orkestra\Bundle\ApplicationBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;

class OfficeUserFactory implements OfficeUserFactoryInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $groupRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->groupRepository = $entityManager->getRepository('OrkestraApplicationBundle:Group');
    }

    /**
     * Creates a new user assigned to the given office
     *
     * @param Office        $office
     * @param UserInterface $user
     * @param array         $roles
     *
     * @return OfficeUser
     */
    public function createOfficeUser(Office $office, UserInterface $user, array $roles = array())
    {
        $officeUser = new OfficeUser($office, $user);

        foreach ($roles as $role) {
            $group = $this->groupRepository->findOneBy(array('role' => $role));

            if ($group) {
                $user->addGroup($group);
            }
        }

        return $officeUser;
    }
}
