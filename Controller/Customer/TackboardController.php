<?php

namespace TerraMar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use TerraMar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;


/**
 * Tackboard controller.
 *
 * @Route("/customer")
 */
class TackboardController extends AbstractController
{
    /**
     * Displays a Customer's Tackboard.
     *
     * @Route("/{id}/notifications", name="customer_tackboard")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function indexAction($id)
    {
        $customer = $this->getRepository('TerraMarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        return array(
            'entity' => $customer,
        );
    }
}
