<?php

namespace TerraMar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\EntityBase;

/**
 * A salesperson
 *
 * @ORM\Entity(repositoryClass="TerraMar\Bundle\SalesBundle\Repository\SalespersonRepository")
 * @ORM\Table(name="terramar_salespeople")
 */
class Salesperson extends EntityBase
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\OneToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\OfficeUser", cascade={"persist"})
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
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $user
     */
    public function __construct(OfficeUser $user)
    {
        $this->user = $user;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\OfficeUser $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
