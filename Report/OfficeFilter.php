<?php

namespace Terramar\Bundle\SalesBundle\Report;

use Orkestra\Bundle\ReportBundle\FilterInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use Terramar\Bundle\SalesBundle\Entity\Office;

/**
 * A simple Filter that allows a callback to modify the QueryBuilder
 */
class OfficeFilter implements FilterInterface
{
    /**
     * @var \Terramar\Bundle\SalesBundle\Entity\Office
     */
    protected $office;

    /**
     * Constructor
     *
     * @param \Terramar\Bundle\SalesBundle\Entity\Office $office
     */
    public function __construct(Office $office)
    {
        $this->office = $office;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     */
    public function apply(QueryBuilder $qb)
    {
        $qb->resetDQLPart('from');
        $qb->from('TerramarSalesBundle:OfficeSnapshot', 's')
            ->andWhere('s.office = :office')
            ->setParameter('office', $this->office);
    }

    /**
     * {@inheritdoc}
     */
    public function bindRequest(Request $request)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $form)
    {
    }
}
