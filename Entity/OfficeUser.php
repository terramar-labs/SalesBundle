<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Bundle\ApplicationBundle\Model\UserInterface;
use Terramar\Bundle\CustomerBundle\Entity\Note;
use Doctrine\Common\Collections\ArrayCollection;
use Terramar\Bundle\SalesBundle\Model\AssignedToInterface;
use Terramar\Bundle\SalesBundle\Model\AssignedByInterface;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A user associated with an Office
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_office_users")
 */
class OfficeUser extends AbstractEntity implements AssignedByInterface, AssignedToInterface
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Model\UserInterface
     *
     * @ORM\OneToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Model\UserInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Terramar\Bundle\SalesBundle\Entity\Alert\OfficeUserAlert", mappedBy="assignedTo", cascade={"persist"})
     */
    protected $alerts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Terramar\Bundle\CustomerBundle\Entity\Note", cascade={"persist"})
     * @ORM\JoinTable(name="terramar_office_user_notes",
     *      joinColumns={@ORM\JoinColumn(name="office_user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $notes;

    /**
     * @param Office $office
     * @param \Orkestra\Bundle\ApplicationBundle\Model\UserInterface $user
     */
    public function __construct(Office $office, UserInterface $user)
    {
        $this->office = $office;
        $this->user = $user;
        $this->alerts = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->user ? $this->user->__toString() : '';
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Model\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $alerts
     */
    public function setAlerts($alerts)
    {
        $this->alerts = $alerts;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @param Note $note
     */
    public function addNote(Note $note)
    {
        $this->notes->add($note);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
