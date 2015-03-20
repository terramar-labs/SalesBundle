<?php

namespace Terramar\Bundle\SalesBundle\Entity\OfficeUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Orkestra\Bundle\ApplicationBundle\Entity\File;
use Orkestra\Bundle\ApplicationBundle\Entity\User;
use Orkestra\Common\Entity\AbstractEntity;
use Terramar\Bundle\SalesBundle\Entity\Office;
use Terramar\Bundle\SalesBundle\Entity\OfficeUser;

/**
 * An OfficeUser's Profile
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_office_user_profiles")
 */
class OfficeUserProfile extends AbstractEntity
{
    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string")
     */
    protected $phone = '';

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\File
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    protected $photo;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\File
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinColumn(name="signature_id", referencedColumnName="id")
     */
    protected $signature;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Terramar\Bundle\SalesBundle\Entity\OfficeUser", mappedBy="profile", cascade={"persist"})
     */
    protected $officeUsers;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\OfficeUser", cascade={"persist"})
     * @ORM\JoinColumn(name="default_office_user_id", referencedColumnName="id")
     */
    protected $defaultOfficeUser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->officeUsers = new ArrayCollection();
    }

    /**
     * @param OfficeUser $officeUser
     */
    public function addOfficeUser(OfficeUser $officeUser)
    {
        if (!$this->officeUsers->contains($officeUser)) {
            $this->officeUsers->add($officeUser);
            $officeUser->setProfile($this);
        }
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|OfficeUser[]
     */
    public function getOfficeUsers()
    {
        return $this->officeUsers;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\OfficeUser $defaultOfficeUser
     */
    public function setDefaultOfficeUser(OfficeUser $defaultOfficeUser)
    {
        $this->addOfficeUser($defaultOfficeUser);
        $this->defaultOfficeUser = $defaultOfficeUser;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\OfficeUser
     */
    public function getDefaultOfficeUser()
    {
        return $this->defaultOfficeUser;
    }

    /**
     * @param Office $office
     *
     * @return OfficeUser|null
     */
    public function getOfficeUser(Office $office)
    {
        return $this->officeUsers->filter(function (OfficeUser $officeUser) use ($office) {
                return $officeUser->getOffice() == $office;
            })->first();
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\File $photo
     */
    public function setPhoto(File $photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\File $signature
     */
    public function setSignature(File $signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

}
