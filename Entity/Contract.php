<?php

namespace TerraMar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Bundle\ApplicationBundle\Entity\File;
use TerraMar\Bundle\SalesBundle\Entity\Contract\FoundByType;
use TerraMar\Bundle\SalesBundle\Entity\Contract\BillingFrequency;
use TerraMar\Bundle\SalesBundle\Entity\Contract\ContractStatus;
use Orkestra\Common\Entity\EntityBase;

/**
 * A contract that a customer has signed
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_contracts")
 */
class Contract extends EntityBase
{
    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile", cascade={"persist"})
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     */
    protected $profile;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Contract\BillingFrequency
     *
     * @ORM\Column(name="billing_frequency", type="enum.terramar.sales.billing_frequency")
     */
    protected $billingFrequency;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Agreement
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Agreement")
     * @ORM\JoinColumn(name="agreement_id", referencedColumnName="id")
     */
    protected $agreement;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Contract\ContractStatus
     *
     * @ORM\Column(name="status", type="enum.terramar.sales.contract_status")
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_start", type="datetime")
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_end", type="datetime")
     */
    protected $dateEnd;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Contract\FoundByType
     *
     * @ORM\Column(name="found_by", type="enum.terramar.sales.found_by_type")
     */
    protected $foundByType;

    /**
     * @var \TerraMar\Bundle\SalesBundle\Entity\Salesperson
     *
     * @ORM\ManyToOne(targetEntity="TerraMar\Bundle\SalesBundle\Entity\Salesperson", cascade={"persist"})
     * @ORM\JoinColumn(name="salesperson_id", referencedColumnName="id")
     */
    protected $salesperson;

    /**
     * @var \Orkestra\Bundle\ApplicationBundle\Entity\File
     *
     * @ORM\OneToOne(targetEntity="Orkestra\Bundle\ApplicationBundle\Entity\File", cascade={"persist"})
     * @ORM\JoinColumn(name="signature_id", referencedColumnName="id")
     */
    protected $signature;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->agreement->__toString();
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Agreement $agreement
     */
    public function setAgreement(Agreement $agreement)
    {
        $this->agreement = $agreement;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Agreement
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Salesperson $salesperson
     */
    public function setSalesperson(Salesperson $salesperson)
    {
        $this->salesperson = $salesperson;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Salesperson
     */
    public function getSalesperson()
    {
        return $this->salesperson;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract\BillingFrequency $billingFrequency
     */
    public function setBillingFrequency(BillingFrequency $billingFrequency)
    {
        $this->billingFrequency = $billingFrequency;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Contract\BillingFrequency
     */
    public function getBillingFrequency()
    {
        return $this->billingFrequency;
    }

    /**
     * @param \DateTime $dateEnd
     */
    public function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime $dateStart
     */
    public function setDateStart(\DateTime $dateStart)
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract\FoundByType $foundByType
     */
    public function setFoundByType(FoundByType $foundByType)
    {
        $this->foundByType = $foundByType;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Contract\FoundByType
     */
    public function getFoundByType()
    {
        return $this->foundByType;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile $profile
     */
    public function setProfile(CustomerSalesProfile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\CustomerSalesProfile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\File $signature
     */
    public function setSignature(File $signature)
    {
        $this->signature = $signature;
    }

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * @param \TerraMar\Bundle\SalesBundle\Entity\Contract\ContractStatus $status
     */
    public function setStatus(ContractStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return \TerraMar\Bundle\SalesBundle\Entity\Contract\ContractStatus
     */
    public function getStatus()
    {
        return $this->status;
    }
}
