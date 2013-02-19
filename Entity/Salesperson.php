<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A salesperson
 *
 * @ORM\Entity(repositoryClass="Terramar\Bundle\SalesBundle\Repository\SalespersonRepository")
 * @ORM\Table(name="terramar_salespeople")
 */
class Salesperson extends AbstractEntity
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\OfficeUser", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function __toString()
    {
        return $this->user->getUser()->__toString();
    }

    /**
     * Constructor
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $user
     */
    public function __construct(OfficeUser $user)
    {
        $this->user = $user;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
