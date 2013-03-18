<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile;
use Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration;
use Orkestra\Common\Entity\AbstractEntity;

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
     * @ORM\Column(name="contact_name", type="string")
     */
    protected $contactName;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address", cascade={"persist"})
     * @ORM\JoinColumn(name="contact_address_id", referencedColumnName="id")
     */
    protected $contactAddress;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address
     *
     * @ORM\ManyToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address", cascade={"persist"})
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     */
    protected $billingAddress;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Office\OfficeConfiguration", mappedBy="office", cascade={"persist"})
     */
    protected $configuration;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile
     *
     * @ORM\OneToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile", mappedBy="office", cascade={"persist"})
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
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address $billingAddress
     */
    public function setBillingAddress($billingAddress)
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
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\Contact\Address $contactAddress
     */
    public function setContactAddress($contactAddress)
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
     * @param \Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile $profile
     */
    public function setProfile(OfficeSalesProfile $profile)
    {
        $profile->setOffice($this);
        $this->profile = $profile;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office\OfficeSalesProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
