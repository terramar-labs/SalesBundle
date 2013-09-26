<?php

namespace Terramar\Bundle\SalesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Bundle\ApplicationBundle\Entity\File;
use Terramar\Bundle\SalesBundle\Entity\Invoice;
use Terramar\Bundle\SalesBundle\Entity\Salesperson;

/**
 * A contract that a customer has signed;
 */
interface ContractInterface extends PersistentModelInterface
{
    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Agreement $agreement
     */
    public function setAgreement(AgreementInterface $agreement);

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Agreement
     */
    public function getAgreement();

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Salesperson $salesperson
     */
    public function setSalesperson(Salesperson $salesperson);

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Salesperson
     */
    public function getSalesperson();

    /**
     * @param mixed
     */
    public function setBillingFrequency($billingFrequency);

    /**
     * @return mixed
     */
    public function getBillingFrequency();

    /**
     * @param \DateTime $dateEnd
     */
    public function setDateEnd(\DateTime $dateEnd = null);

    /**
     * @return \DateTime
     */
    public function getDateEnd();

    /**
     * @param \DateTime $dateStart
     */
    public function setDateStart(\DateTime $dateStart);

    /**
     * @return \DateTime
     */
    public function getDateStart();

    /**
     * @param string $marketingType
     */
    public function setMarketingType($marketingType);

    /**
     * @return string
     */
    public function getMarketingType();

    /**
     * @param \Orkestra\Bundle\ApplicationBundle\Entity\File $signature
     */
    public function setSignature(File $signature);

    /**
     * @return \Orkestra\Bundle\ApplicationBundle\Entity\File
     */
    public function getSignature();

    /**
     * @param mixed
     */
    public function setStatus($status);

    /**
     * @return mixed
     */
    public function getStatus();

    /**
     * @param Invoice $invoice
     */
    public function addInvoice(Invoice $invoice);

    /**
     * @param \Doctrine\Common\Collections\Collection $invoices
     */
    public function setInvoices($invoices);

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvoices();
}
