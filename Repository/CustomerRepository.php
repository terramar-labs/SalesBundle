<?php

namespace TerraMar\Bundle\SalesBundle\Repository;

use TerraMar\Bundle\CustomerBundle\Repository\CustomerRepository as BaseCustomerRepository;
use TerraMar\Bundle\SalesBundle\Entity\Office;

class CustomerRepository extends BaseCustomerRepository
{
    public function findOneByIdAndOffice($id, Office $office)
    {
        $dql ='SELECT c
            FROM  TerraMarSalesBundle:CustomerSalesProfile csp,
                  TerraMarCustomerBundle:Customer c
            WHERE csp.customer = c
            AND c.id = :id
            AND csp.office = :office';
        $query = $this->_em->createQuery($dql);
        $query->setParameters(array('id' => $id, 'office' => $office));

        return $query->getOneOrNullResult();
    }

    public function findAllByOffice(Office $office, $limit = null)
    {
        $dql ='SELECT c
            FROM  TerraMarSalesBundle:CustomerSalesProfile csp,
                  TerraMarCustomerBundle:Customer c
            WHERE csp.customer = c
            AND csp.active = true
            AND c.active = true
            AND csp.office = :office';
        $query = $this->_em->createQuery($dql);
        $query->setParameters(array('office' => $office));

        if (null !== $limit) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    public function findRecentByOffice(Office $office)
    {
        $dql ='SELECT c
            FROM  TerraMarSalesBundle:CustomerSalesProfile csp,
                  TerraMarCustomerBundle:Customer c
            WHERE csp.customer = c
            AND csp.office = :office
            ORDER BY c.dateCreated DESC';
        $query = $this->_em->createQuery($dql);
        $query->setParameters(array('office' => $office));
        $query->setMaxResults(25);

        return $query->getResult();
    }

    public function simpleSearchByOffice(Office $office, $searchTerm)
    {
        $dql ='SELECT c
            FROM  TerraMarSalesBundle:CustomerSalesProfile csp,
                  TerraMarCustomerBundle:Customer c
            WHERE csp.customer = c
            AND csp.office = :office
            AND (c.firstName LIKE :searchTerm
            OR c.lastName LIKE :searchTerm
            OR c.emailAddress LIKE :searchTerm)
            ORDER BY c.dateCreated DESC';
        $query = $this->_em->createQuery($dql);
        $query->setParameters(array('office' => $office, 'searchTerm' => '%' . $searchTerm . '%'));
        $query->setMaxResults(25);

        return $query->getResult();
    }
}
