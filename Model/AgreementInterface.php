<?php

namespace Terramar\Bundle\SalesBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Orkestra\Common\Entity\AbstractEntity;
use Terramar\Bundle\SalesBundle\Entity\Office;

/**
 * An agreement
 *
 * @ORM\Entity(repositoryClass="Terramar\Bundle\SalesBundle\Repository\AgreementRepository")
 * @ORM\Table(name="terramar_agreements")
 */
interface AgreementInterface extends PersistentModelInterface
{
    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     */
    public function setOffice(Office $office);

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Office
     */
    public function getOffice();

    /**
     * @param string $agreementBody
     */
    public function setAgreementBody($agreementBody);

    /**
     * @return string
     */
    public function getAgreementBody();

    /**
     * @param array $availableFrequencies
     */
    public function setBillingFrequencies($availableFrequencies);

    /**
     * @return array
     */
    public function getBillingFrequencies();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $invoiceIntro
     */
    public function setInvoiceIntro($invoiceIntro);

    /**
     * @return string
     */
    public function getInvoiceIntro();

    /**
     * @param int $length
     */
    public function setLength($length);

    /**
     * @return int
     */
    public function getLength();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $welcomeLetter
     */
    public function setWelcomeLetter($welcomeLetter);

    /**
     * @return string
     */
    public function getWelcomeLetter();
}
