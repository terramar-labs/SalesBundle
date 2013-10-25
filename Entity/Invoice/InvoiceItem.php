<?php

namespace Terramar\Bundle\SalesBundle\Entity\Invoice;

use Doctrine\ORM\Mapping as ORM;
use Terramar\Bundle\SalesBundle\Entity\Invoice;
use Orkestra\Common\Entity\AbstractEntity;

/**
 * A line item on an invoice
 *
 * @ORM\Entity
 * @ORM\Table(name="terramar_invoice_items")
 */
class InvoiceItem extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string")
     */
    protected $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=8, scale=2)
     */
    protected $price;

    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Invoice
     *
     * @ORM\ManyToOne(targetEntity="Terramar\Bundle\SalesBundle\Entity\Invoice", inversedBy="items")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    protected $invoice;

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \Terramar\Bundle\SalesBundle\Entity\Invoice $invoice
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return \Terramar\Bundle\SalesBundle\Entity\Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}
