<?php

namespace Terramar\Bundle\SalesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;
use Terramar\Bundle\SalesBundle\Model\AgreementInterface;

/**
 * An agreement
 *
 * @ORM\MappedSuperclass
 */
abstract class Agreement extends AbstractEntity implements AgreementInterface
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
     * @ORM\Column(name="description", type="string")
     */
    protected $description = '';

    /**
     * @var array
     *
     * @ORM\Column(name="billing_frequencies", type="array")
     */
    protected $billingFrequencies;

    /**
     * @var integer The length, in months
     *
     * @ORM\Column(name="length", type="integer")
     */
    protected $length;

    /**
     * @var string
     *
     * @ORM\Column(name="agreement_body", type="text")
     */
    protected $agreementBody = '';

    /**
     * @var string
     *
     * @ORM\Column(name="welcome_letter", type="text")
     */
    protected $welcomeLetter = '';

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_intro", type="text")
     */
    protected $invoiceIntro = '';

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Office")
     * @ORM\JoinColumn(name="office_id", referencedColumnName="id")
     */
    protected $office;

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     */
    public function setOffice(Office $office)
    {
        $this->office = $office;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param string $agreementBody
     */
    public function setAgreementBody($agreementBody)
    {
        $this->agreementBody = (string)$agreementBody;
    }

    /**
     * @return string
     */
    public function getAgreementBody()
    {
        return $this->agreementBody;
    }

    /**
     * @param array $availableFrequencies
     */
    public function setBillingFrequencies($availableFrequencies)
    {
        $this->billingFrequencies = $availableFrequencies;
    }

    /**
     * @return array
     */
    public function getBillingFrequencies()
    {
        return $this->billingFrequencies;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = (string)$description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $invoiceIntro
     */
    public function setInvoiceIntro($invoiceIntro)
    {
        $this->invoiceIntro = (string)$invoiceIntro;
    }

    /**
     * @return string
     */
    public function getInvoiceIntro()
    {
        return $this->invoiceIntro;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
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
     * @param string $welcomeLetter
     */
    public function setWelcomeLetter($welcomeLetter)
    {
        $this->welcomeLetter = (string)$welcomeLetter;
    }

    /**
     * @return string
     */
    public function getWelcomeLetter()
    {
        return $this->welcomeLetter;
    }
}
