<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface;
use Terramar\Bundle\CustomerBundle\Entity\Note;
use Terramar\Bundle\CustomerBundle\Model\NoteInterface;
use Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile;
use Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration;
use Orkestra\Common\Entity\AbstractEntity;
use Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface;

/**
 * A physical location of a company
 *
 * @ORM\Entity(repositoryClass="Terramar\Bundle\SalesBundle\Repository\OfficeRepository")
 * @ORM\Table(name="terramar_offices")
 */
class Office extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string")
     */
    protected $url = '';

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string")
     */
    protected $fax = '';

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\File
     *
     * @ORM\OneToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinColumn(name="logo_file_id", referencedColumnName="id")
     */
    protected $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    protected $emailAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="contact_name", type="string")
     */
    protected $contactName;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="contact_address_id", referencedColumnName="id")
     */
    protected $contactAddress;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     */
    protected $billingAddress;

    /**
     * @var \Terramar\Bundle\SalesBundle\Model\Office\OfficeConfigurationInterface
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\SalesBundle\Model\Office\OfficeConfigurationInterface", mappedBy="office", cascade={"persist"})
     */
    protected $configuration;

    /**
     * @var \Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\SalesBundle\Model\OfficeSalesProfileInterface", mappedBy="office", cascade={"persist"})
     */
    protected $profile;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Terramar\Bundle\CustomerBundle\Model\NoteInterface", cascade={"persist"})
     * @ORM\JoinTable(name="terramar_office_notes",
     *      joinColumns={@ORM\JoinColumn(name="office_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="note_id", referencedColumnName="id", unique=true)}
     * )
     */
    protected $notes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface $billingAddress
     */
    public function setBillingAddress(AddressInterface $billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Model\Contact\AddressInterface $contactAddress
     */
    public function setContactAddress(AddressInterface $contactAddress)
    {
        $this->contactAddress = $contactAddress;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address
     */
    public function getContactAddress()
    {
        return $this->contactAddress;
    }

    /**
     * @param string $contactName
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
    }

    /**
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = (string)$fax;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\File $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $parent
     */
    public function setParent(Office $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration $configuration
     */
    public function setConfiguration(OfficeConfiguration $configuration)
    {
        $configuration->setOffice($this);
        $this->configuration = $configuration;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param OfficeSalesProfileInterface $profile
     */
    public function setProfile(OfficeSalesProfileInterface $profile)
    {
        $profile->setOffice($this);
        $this->profile = $profile;
    }

    /**
     * @return OfficeSalesProfileInterface
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @param NoteInterface $note
     */
    public function addNote(NoteInterface $note)
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
