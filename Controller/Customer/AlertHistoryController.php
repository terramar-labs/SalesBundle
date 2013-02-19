<?php

namespace Terramar\Bundle\SalesBundle\Controller\Customer;

use Symfony\Component\HttpFoundation\Request;
use Terramar\Bundle\SalesBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Alert  history controller.
 *
 * @Route("")
 */
class AlertHistoryController extends AbstractController
{
    /**
     * Displays a users Historical.
     *
     * @Route("/{id}/alert/history", name="customer_alert_history")
     * @Template()
     * @Secure(roles="ROLE_TACKBOARD_READ")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('TerramarCustomerBundle:Customer')->findOneByIdAndOffice($id, $this->getCurrentOffice());

        if (!$customer) {
            throw $this->createNotFoundException('Unable to locate Customer entity');
        }

        $profile = $em->getRepository('TerramarSalesBundle:CustomerSalesProfile')->findOneByCustomer($customer);

        if (!$profile) {
            throw $this->createNotFoundException('Unable to find Customer Sales Profile entity.');
        }

        $alerts = $em->getRepository('TerramarSalesBundle:Alert\CustomerAlert')->findAlertHistorybyProfile($profile);

        return array(
            'entity' => $customer,
            'entities' => $alerts,
        );
    }

}
